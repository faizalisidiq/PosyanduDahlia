import Chart from 'chart.js/auto';

window.initKmsChart = function(canvasId, gender, anthropometryStandards, childGrowthData) {
    const ctx = document.getElementById(canvasId).getContext('2d');

    // Extract standardized data (0-60 months)
    // Expect anthropometryStandards to be an array of objects: { age_in_months, sd_3_neg, sd_2_neg, median, sd_2_pos, sd_3_pos }
    const labels = anthropometryStandards.map(data => data.age_in_months);
    const sd3neg = anthropometryStandards.map(data => data.sd_3_neg);
    const sd2neg = anthropometryStandards.map(data => data.sd_2_neg);
    const median = anthropometryStandards.map(data => data.median);
    const sd2pos = anthropometryStandards.map(data => data.sd_2_pos);
    const sd3pos = anthropometryStandards.map(data => data.sd_3_pos);

    // Filter child data to match the months we have standards for (usually 0-60)
    // Child data: [{ age_in_months: 0, weight: 3.5 }, ...]
    // We need to map this to the labels array.
    // Create an array of nulls same length as labels
    // Map Child Data to Labels
    const childWeightData = labels.map(labelMonth => {
        // Find a record where age_in_months matches the labelMonth (allowing for string/int difference)
        const match = childGrowthData.find(record => parseInt(record.age_in_months) === parseInt(labelMonth));
        return match ? match.weight : null;
    });

    const kmsColor = gender === 'male' ? 'rgba(54, 162, 235, 0.2)' : 'rgba(255, 99, 132, 0.2)';
    const kmsBorderColor = gender === 'male' ? 'rgba(54, 162, 235, 1)' : 'rgba(255, 99, 132, 1)';

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Berat Badan Anak (kg)',
                    data: childWeightData,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    tension: 0.1,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: false,
                    order: 1 // Top layer
                },
                {
                    label: 'SD +3',
                    data: sd3pos,
                    borderColor: 'rgba(0,0,0,0)', // Transparent border
                    backgroundColor: 'rgba(255, 205, 86, 0.2)', // Yellowish warning area
                    pointRadius: 0,
                    fill: '+1', // Fill to next dataset (SD +2)
                    order: 2
                },
                {
                    label: 'SD +2',
                    data: sd2pos,
                    borderColor: 'rgba(0,0,0,0)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Greenish normal area
                    pointRadius: 0,
                    fill: '-1', // Fill to previous dataset? No, we want bands.
                    // ChartJS filling is tricky. Let's try simple stacking or fill: 'origin' logic?
                    // "fill: '+1'" fills to the dataset with index current_index + 1
                    // Let's reorder: +3, +2, -2, -3.
                    // Actually simpler to just draw lines or create bands.
                    // Let's use simple lines for Standards, maybe fill between them?
                    // To fill between lines:
                    // Set fill: 2 (index of SD+2 dataset) for SD+3?
                    // Best practice for KMS is colored bands:
                    // >+3 : Obesitas ?
                    // +2 to +3 : Gizi Lebih
                    // -2 to +2 : Gizi Baik (Green)
                    // -3 to -2 : Gizi Kurang (Yellow)
                    // <-3 : Gizi Buruk (Red)
                    
                    // Let's just draw the lines for now to ensure it works, with light fills.
                    fill: false, // temporarily no fill
                    borderDash: [5, 5],
                    order: 3
                },
                {
                    label: 'Median',
                    data: median,
                    borderColor: 'rgba(100, 100, 100, 0.5)',
                    borderWidth: 2,
                    pointRadius: 0,
                    fill: false,
                    order: 4
                },
                {
                    label: 'SD -2',
                    data: sd2neg,
                    borderColor: 'rgba(0,0,0,0)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    pointRadius: 0,
                    fill: false,
                    borderDash: [5, 5],
                    order: 5
                },
                {
                    label: 'SD -3',
                    data: sd3neg,
                    borderColor: 'rgba(0,0,0,0)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    pointRadius: 0,
                    fill: '-1', // Fill to SD -2? 
                    // Let's stick to standard lines for clarity first:
                    // SD3+, SD2+, Median, SD2-, SD3-
                    order: 6
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Grafik Pertumbuhan KMS (0 - 64 Bulan)'
                },
                tooltip: {
                    callbacks: {
                        title: (context) => {
                            return `Umur: ${context[0].label} Bulan`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Umur (Bulan)'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Berat Badan (kg)'
                    },
                    suggestedMin: 0,
                    suggestedMax: 25
                }
            }
        }
    });
}
