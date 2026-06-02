import Chart from 'chart.js/auto';

const initDashboard = () => {
    if (!window.dashboardData) return;
    const data = window.dashboardData;

    const safeInit = (id, config) => {
        const ctx = document.getElementById(id);
        if (!ctx) return;
        
        try {
            // Clean up existing chart if it exists
            const existingChart = Chart.getChart(ctx);
            if (existingChart) {
                existingChart.destroy();
            }
            new Chart(ctx, config);
        } catch (e) {
            console.error(`Error initializing chart ${id}:`, e);
        }
    };

    const toArray = (val) => {
        if (Array.isArray(val)) return val;
        if (val && typeof val === 'object') return Object.values(val);
        return [];
    };

    // 1. Monthly Trends
    safeInit('trendChart', {
        type: 'bar',
        data: {
            labels: toArray(data.months),
            datasets: [
                {
                    label: 'Total Kunjungan',
                    data: toArray(data.visits),
                    backgroundColor: 'rgba(13, 148, 136, 0.2)',
                    borderColor: 'rgb(13, 148, 136)',
                    borderWidth: 2,
                    borderRadius: 4,
                    order: 2
                },
                {
                    type: 'line',
                    label: 'Anak Baru',
                    data: toArray(data.newChildren),
                    borderColor: 'rgb(245, 158, 11)',
                    backgroundColor: 'rgb(245, 158, 11)',
                    borderWidth: 3,
                    tension: 0.4,
                    pointRadius: 4,
                    order: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Children Gender
    const gMale = Number(data.gender?.male || 0);
    const gFemale = Number(data.gender?.female || 0);
    safeInit('genderChart', {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [gMale, gFemale],
                backgroundColor: ['#60A5FA', '#F472B6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // 3. Nutritional Status
    const sLabels = toArray(data.status?.labels);
    const sValues = toArray(data.status?.values);
    safeInit('statusChart', {
        type: 'bar',
        data: {
            labels: sLabels.length > 0 ? sLabels : ['Belum Ada'],
            datasets: [{
                label: 'Jumlah Anak',
                data: sValues.length > 0 ? sValues : [0],
                backgroundColor: sLabels.map(l => {
                    const lower = String(l).toLowerCase();
                    if (lower.includes('buruk') || lower.includes('stunted')) return '#F87171';
                    if (lower.includes('kurang')) return '#FBBF24';
                    return '#34D399';
                }),
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                 x: { beginAtZero: true, ticks: { precision: 0 } },
                 y: { grid: { display: false } }
            }
        }
    });

    // 4. Staff Performance
    const staffArr = toArray(data.topStaff);
    safeInit('staffChart', {
        type: 'bar',
        data: {
            labels: staffArr.length > 0 ? staffArr.map(s => (s.name || 'Staff').split(' ')[0]) : ['Belum Ada'],
            datasets: [{
                label: 'Pemeriksaan',
                data: staffArr.length > 0 ? staffArr.map(s => s.count || 0) : [0],
                backgroundColor: '#818CF8',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });

    // 5. Age Distribution
    const ageLabelsRaw = toArray(data.age?.labels);
    const ageValuesRaw = toArray(data.age?.values);
    
    // Ensure values are numbers
    const ageDataPoints = ageValuesRaw.map(v => Number(v) || 0);
    const ageLabelsFinal = ageLabelsRaw.length > 0 ? ageLabelsRaw.map(l => String(l)) : ['0-12 bln', '13-24 bln', '25-36 bln', '37-48 bln', '49-60 bln'];

    safeInit('ageChart', {
        type: 'bar',
        data: {
            labels: ageLabelsFinal,
            datasets: [{
                label: 'Jumlah Balita',
                data: ageDataPoints.length > 0 ? ageDataPoints : [0, 0, 0, 0, 0],
                backgroundColor: '#10B981', // Emerald-500
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { 
                        precision: 0,
                        stepSize: 1
                    } 
                },
                x: { grid: { display: false } }
            }
        }
    });
};

document.addEventListener('DOMContentLoaded', initDashboard);
