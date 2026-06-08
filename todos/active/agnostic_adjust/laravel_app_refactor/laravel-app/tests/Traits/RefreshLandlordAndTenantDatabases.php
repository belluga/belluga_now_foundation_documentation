<?php

namespace Tests\Traits;

use App\Models\Landlord\Landlord;
use App\Models\Landlord\LandlordUser;
use App\Models\Landlord\Tenant;
use App\Models\Tenants\Account;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait RefreshLandlordAndTenantDatabases
{
    protected static bool $migrationsRan = false;

    protected function prepareAuthenticatedHarnessState(): void
    {
        if (! property_exists(static::class, 'bootstrapped')) {
            return;
        }

        if (static::$bootstrapped) {
            return;
        }

        if (! method_exists($this, 'initializeSystem')) {
            return;
        }

        $this->refreshLandlordAndTenantDatabases();
        $this->initializeSystem();
        static::$bootstrapped = true;
    }

    protected function migrationCommand(): string
    {
        $landlordDsn = (string) env('DB_URI_LANDLORD', '');
        $tenantDsn = (string) env('DB_URI_TENANTS', '');
        $dsn = $landlordDsn !== '' ? $landlordDsn : $tenantDsn;

        // dropDatabase races with subsequent createCollection on single-node replica sets.
        // Always use non-destructive migrate + collection-level wipe for safety.
        if ($dsn !== '' && str_contains($dsn, 'mongodb')) {
            return 'migrate';
        }

        return 'migrate:fresh';
    }

    protected function refreshLandlordAndTenantDatabases(): void
    {
        if (property_exists(static::class, 'bootstrapped')) {
            static::$bootstrapped = false;
        }

        $this->resetRuntimeState();

        $tenantDatabaseNames = Tenant::query()
            ->pluck('database')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $landlordDatabase = DB::connection('landlord')->getDatabase();
        $tenantDatabase = DB::connection('tenant')->getDatabase();
        $landlordDsn = (string) env('DB_URI_LANDLORD', '');
        $tenantDsn = (string) env('DB_URI_TENANTS', '');
        $dsn = $landlordDsn !== '' ? $landlordDsn : $tenantDsn;
        $isAtlas = $dsn !== '' && str_contains($dsn, 'mongodb'); // Always use collection-level wipe to avoid drop race conditions

        Log::info('Tests: landlord collections before wipe', [
            'collections' => iterator_to_array($landlordDatabase->listCollectionNames()),
            'landlords_count' => Landlord::query()->count(),
            'tenants_count' => Tenant::query()->count(),
        ]);
        Log::info('Tests: tenant collections before wipe', [
            'collections' => iterator_to_array($tenantDatabase->listCollectionNames()),
        ]);
        if ($isAtlas) {
            // Drop collections individually (safe, no race condition unlike dropDatabase)
            foreach ($landlordDatabase->listCollectionNames() as $collectionName) {
                $landlordDatabase->dropCollection($collectionName);
            }

            foreach ($tenantDatabase->listCollectionNames() as $collectionName) {
                $tenantDatabase->dropCollection($collectionName);
            }

            // Also discover tenant databases by prefix (covers orphaned DBs from previous runs)
            $tenantClient = DB::connection('tenant')->getMongoClient();
            $defaultTenantDb = (string) config('database.connections.tenant.database');
            $allTenantDbs = collect($tenantDatabaseNames);

            foreach ($tenantClient->listDatabases() as $databaseInfo) {
                $dbName = $databaseInfo->getName();
                if (str_starts_with($dbName, 'tenant_') || $dbName === $defaultTenantDb) {
                    $allTenantDbs->push($dbName);
                }
            }

            foreach ($allTenantDbs->unique()->values() as $databaseName) {
                $database = $tenantClient->selectDatabase($databaseName);
                foreach ($database->listCollectionNames() as $collectionName) {
                    $database->dropCollection($collectionName);
                }
            }

            static::$migrationsRan = false;
        } else {
            $landlordDatabase->drop();
            $tenantDatabase->drop();

            if (! empty($tenantDatabaseNames)) {
                $tenantClient = DB::connection('tenant')->getMongoClient();

                foreach ($tenantDatabaseNames as $databaseName) {
                    $tenantClient->selectDatabase($databaseName)->drop();
                }
            }

            static::$migrationsRan = false;
        }

        Log::info('Tests: landlord collections after wipe', [
            'collections' => iterator_to_array($landlordDatabase->listCollectionNames()),
            'landlords_count' => Landlord::query()->count(),
            'tenants_count' => Tenant::query()->count(),
        ]);
        Log::info('Tests: tenant collections after wipe', [
            'collections' => iterator_to_array($tenantDatabase->listCollectionNames()),
        ]);

        $this->resetRuntimeState();

        if (static::$migrationsRan) {
            return;
        }

        $command = $this->migrationCommand();
        $tenantPaths = $this->tenantMigrationPathArgs();

        Artisan::call($command, [
            '--database' => 'landlord',
            '--path' => 'database/migrations/landlord',
        ]);

        Artisan::call(sprintf(
            'tenants:artisan "%s --database=tenant %s"',
            $command,
            $tenantPaths
        ));

        LandlordUser::query()->forceDelete();
        Landlord::query()->delete();
        Tenant::query()->forceDelete();

        $this->resetRuntimeState();

        static::$migrationsRan = true;
    }

    private function resetRuntimeState(): void
    {
        Tenant::forgetCurrent();
        Account::current()?->forget();

        Context::forget((string) config('multitenancy.current_tenant_context_key', 'tenantId'));
        Context::forget('accountId');

        app()->forgetInstance((string) config('multitenancy.current_tenant_container_key', 'currentTenant'));
        app()->forgetInstance('currentAccount');

        Landlord::forgetSingletonCache();
        Log::withoutContext();
        $this->resetGlobalLabelState();
    }

    private function resetGlobalLabelState(): void
    {
        global $params;

        $params = [];
    }

    protected function tenantMigrationPathArgs(): string
    {
        $paths = (array) config('multitenancy.tenant_migration_paths', ['database/migrations/tenants']);

        return implode(' ', array_map(
            static fn (string $path): string => sprintf('--path=%s', $path),
            $paths
        ));
    }
}
