#!/bin/bash
# Validação de classes específicas do Belluga Now antes do cache.

required_runtime_classes=(
    "Belluga\\Events\\Jobs\\PublishScheduledEventsJob"
    "Belluga\\Invites\\Http\\Api\\v1\\Controllers\\InviteFeedController"
    "App\\Providers\\PackageIntegration\\InvitesIntegrationServiceProvider"
    "Belluga\\Favorites\\Contracts\\FavoritesRegistryContract"
    "App\\Providers\\PackageIntegration\\FavoritesIntegrationServiceProvider"
)

for required_runtime_class in "${required_runtime_classes[@]}"; do
    ensure_runtime_class_resolvable "$required_runtime_class"
done
