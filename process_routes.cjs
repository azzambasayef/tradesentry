const fs = require('fs');
const searoute = require('searoute-js');

// Get args
const inputFile = process.argv[2];
const outputFile = process.argv[3];

if (!inputFile || !outputFile) {
    console.error("Usage: node process_routes.js <input.json> <output.json>");
    process.exit(1);
}

const data = JSON.parse(fs.readFileSync(inputFile, 'utf-8'));
const results = [];

data.forEach(ship => {
    try {
        const origin = {
            type: 'Feature',
            geometry: { type: 'Point', coordinates: [parseFloat(ship.o_lng), parseFloat(ship.o_lat)] }
        };
        const dest = {
            type: 'Feature',
            geometry: { type: 'Point', coordinates: [parseFloat(ship.d_lng), parseFloat(ship.d_lat)] }
        };
        
        // Calculate route using Dijkstra maritime graph
        const route = searoute(origin, dest);
        
        results.push({
            id: ship.id,
            geometry: route ? route.geometry.coordinates : null
        });
    } catch (e) {
        results.push({
            id: ship.id,
            geometry: null
        });
    }
});

fs.writeFileSync(outputFile, JSON.stringify(results));
console.log("Geoprocessing complete. Generated routes for " + results.length + " ships.");
