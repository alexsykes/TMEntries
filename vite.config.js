import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],


    build: {
        rollupOptions: {
            output:{
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return id.toString().split('node_modules/')[1].split('/')[0].toString();
                    }
                }
            }
        }
    }
});


function manualChunks(id, { getModuleInfo }) {
    const match = /.*\.strings\.(\w+)\.js/.exec(id);
    if (match) {
        const language = match[1]; // e.g. "en"
        const dependentEntryPoints = [];

        // we use a Set here so we handle each module at most once. This
        // prevents infinite loops in case of circular dependencies
        const idsToHandle = new Set(getModuleInfo(id).dynamicImporters);

        for (const moduleId of idsToHandle) {
            const { isEntry, dynamicImporters, importers } =
                getModuleInfo(moduleId);
            if (isEntry || dynamicImporters.length > 0)
                dependentEntryPoints.push(moduleId);

            // The Set iterator is intelligent enough to iterate over
            // elements that are added during iteration
            for (const importerId of importers) idsToHandle.add(importerId);
        }

        // If there is a unique entry, we put it into a chunk based on the
        // entry name
        if (dependentEntryPoints.length === 1) {
            return `${
                dependentEntryPoints[0].split('/').slice(-1)[0].split('.')[0]
            }.strings.${language}`;
        }
        // For multiple entries, we put it into a "shared" chunk
        if (dependentEntryPoints.length > 1) {
            return `shared.strings.${language}`;
        }
    }
}

