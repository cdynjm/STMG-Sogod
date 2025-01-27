<script setup>
import { ref, onMounted, watch } from 'vue';
import ApexCharts from 'vue3-apexcharts';
import { Link, useForm } from '@inertiajs/vue3';

import Navbar from '@/Layouts/Navbar.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
import Footer from '@/Layouts/Footer.vue';

const props = defineProps({
    auth: Array,
    unpaid: Number,
    vehicle: Array,
    vehicleCount: Number,
    violation: Array,
    violationList: Array,
    topViolations: Object,
    year: String,
    driver: Array
});

const searchForm = useForm({
    search: null,
    result: 0,
});

const currentYear = new Date().getFullYear();
const startYear = 2020;
const years = Array.from({ length: currentYear - startYear + 1 }, (_, i) => startYear + i);

const searchYear = () => {
    searchForm.get(route('search-year-analytics'));
};

const month = Array(12).fill(0);

const series = ref([
    {
        name: "Ticketed",
        data: month
    }
]);

const chartOptions = ref({
    chart: {
        type: 'line',
        height: 300,
        events: {
            dataPointMouseEnter: (event, chartContext, config) => {
                console.log('Hovered data:', config.w.config.series[config.seriesIndex].data[config.dataPointIndex]);
            }
        }
    },
    dataLabels: {
        enabled: true,
        formatter: (val) => {
            return val;
        }
    },
    stroke: {
        curve: 'smooth'
    },
    title: {
        text: 'Monthly Data',
        align: 'left'
    },
    grid: {
        row: {
            colors: ['#f3f3f3', 'transparent'],
            opacity: 0.5
        },
    },
    xaxis: {
        categories: [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'
        ],
    },
    tooltip: {
        enabled: true,
        x: {
            format: 'dd/MM/yy HH:mm'
        },
        y: {
            formatter: (val) => {
                return `${val}`;
            }
        }
    },
});



// Data for the violations
const series2 = ref([]);

// Labels for each violation
const labels = ref([]);

// Chart options
const chartOptions2 = ref({
    chart: {
        type: 'bar',
        height: 350
    },
    plotOptions: {
        bar: {
            horizontal: true, // Setting the bar chart to be horizontal
            borderRadius: 5
        }
    },
    xaxis: {
        categories: labels.value, // Initially set categories to labels.value
    },
    fill: {
        opacity: 1
    },
    stroke: {
        width: 1,
        colors: undefined
    },
    dataLabels: {
        enabled: false
    },
    legend: {
        position: 'left',
        floating: false,
        itemMargin: {},
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return val === 0 ? '0' : Math.round(val).toString();
            }
        }
    },
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 300
            },
            legend: {
                position: 'bottom',
                floating: false,
                itemMargin: {},
            }
        }
    }]
});

// Watch for changes in labels.value to update chartOptions2
watch(labels, (newLabels) => {
    chartOptions2.value = {
        ...chartOptions2.value,
        xaxis: {
            categories: newLabels // Update categories in x-axis with new labels
        }
    };
});

const series3 = ref([]); // Declare series3
const chartOptions3 = ref({});

const series4 = ref([]); // Declare series3
const chartOptions4 = ref({});

// Function to populate violation data
const populateViolationData = () => {
    if (props.topViolations && Array.isArray(props.topViolations)) {
        // Filter violations to include only those with a non-zero count
        const filteredViolations = props.topViolations.filter(v => v.count > 0);

        // Map the filtered violations for labels and counts
        labels.value = filteredViolations.map(v => v.name); // Y-axis labels
        const counts = filteredViolations.map(v => v.count); // Bar lengths

        // Assign the filtered data to the series
        series2.value = [{
            name: 'Violations',
            data: counts,
        }];
    }
};

// Function to populate monthly data
const populateMonthData = () => {
    if (props.vehicle && Array.isArray(props.vehicle)) {
        props.vehicle.forEach(v => {
            const monthIndex = new Date(v.created_at).getMonth();
            month[monthIndex]++;
        });
    }
};

const populateAgeGroupData = () => {
    if (props.vehicle && Array.isArray(props.vehicle)) {
        
        const ageGroups = {
            "15-20 years": { count: 0, ages: [] },
            "21-30 years": { count: 0, ages: [] },
            "31-40 years": { count: 0, ages: [] },
            "41-50 years": { count: 0, ages: [] },
            "51+ years": { count: 0, ages: [] },
        };

        // Calculate age group counts and store the ages
        props.vehicle.forEach(v => {
            if (v.age) {
                const vehicleAge = v.age;
                if (vehicleAge >= 15 && vehicleAge <= 20) {
                    ageGroups["15-20 years"].count++;
                    ageGroups["15-20 years"].ages.push(vehicleAge);
                } else if (vehicleAge >= 21 && vehicleAge <= 30) {
                    ageGroups["21-30 years"].count++;
                    ageGroups["21-30 years"].ages.push(vehicleAge);
                } else if (vehicleAge >= 31 && vehicleAge <= 40) {
                    ageGroups["31-40 years"].count++;
                    ageGroups["31-40 years"].ages.push(vehicleAge);
                } else if (vehicleAge >= 41 && vehicleAge <= 50) {
                    ageGroups["41-50 years"].count++;
                    ageGroups["41-50 years"].ages.push(vehicleAge);
                } else if (vehicleAge >= 51) {
                    ageGroups["51+ years"].count++;
                    ageGroups["51+ years"].ages.push(vehicleAge);
                }
            }
        });

        // Prepare the data for chart series and tooltip
        series3.value = [{
            name: "Violations",
            data: Object.values(ageGroups).map(group => group.count),
        }];

        // Customize tooltip to show the list of ages within each group
        chartOptions3.value = {
            chart: {
                type: "line",
                toolbar: {
                    show: false,
                },
            },
            xaxis: {
                categories: Object.keys(ageGroups),
                title: {
                    text: "Age Group",
                },
            },
            yaxis: {
                title: {
                    text: "Number of Violations",
                },
            },
            title: {
                text: "Most Violated Age Groups",
                align: "center",
            },
            tooltip: {
                custom: ({ seriesIndex, dataPointIndex, w }) => {
                    const ageGroup = Object.keys(ageGroups)[dataPointIndex];
                    const agesInGroup = ageGroups[ageGroup].ages;
                    return `
                        <div class="tooltip-custom">
                            <strong>${ageGroup}</strong><br>
                            Violations: ${w.globals.series[seriesIndex][dataPointIndex]}<br>
                            Ages: ${agesInGroup.join(', ')}
                        </div>
                    `;
                }
            }
        };
    }
};

// Function to populate gender data for pie chart
const populateGenderData = () => {
  if (props.driver && Array.isArray(props.driver)) {
    // Initialize male and female counts
    const genderCounts = {
      "Male": 0,
      "Female": 0,
    };

    // Count male and female drivers
    props.driver.forEach(d => {
      if (d.gender) {
        if (d.gender === '1') {
          genderCounts["Male"]++;
        } else if (d.gender === '2') {
          genderCounts["Female"]++;
        }
      }
    });

    // Prepare the data for gender chart series
    series4.value = [genderCounts["Male"], genderCounts["Female"]];

    // Customize the gender chart
    chartOptions4.value = {
      chart: {
        type: "pie",
        toolbar: {
          show: false,
        },
      },
      labels: ["Male", "Female"], // Gender categories
      title: {
        text: "Gender Distribution of Drivers",
        align: "center",
      },
      tooltip: {
        y: {
          formatter: (value) => {
            return `${value} drivers`; // Show the count in tooltip
          },
        },
      },
    };
  }
};

onMounted(() => {
    populateMonthData();
    populateViolationData();
    populateAgeGroupData(); // Existing function for age group chart
    populateGenderData(); // Call the new function for gender chart
    searchForm.search = props.year;
});

</script>

<template>
    <div class="wrapper">
        <Navbar :page="'Dashboard'" />
        <Sidebar />

        <div class="page-content">
            <div class="container-fluid">

                <!-- ========== Page Title Start ========== -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="mb-0"></h4>
                            <ol class="breadcrumb mb-0">

                            </ol>
                        </div>
                    </div>
                </div>

                <!-- ========== Page Title End ========== -->

                <!-- Start here.... -->
                <div class="row">
                    <div class="col-md-6 col-xl-6">
                        <Link :href="route('admin.ticket-history')">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-md bg-light bg-opacity-50 rounded">
                                            <iconify-icon icon="fluent:road-cone-20-filled"
                                                class="fs-32 text-warning avatar-title"></iconify-icon>
                                        </div>
                                    </div> <!-- end col -->
                                    <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate fw-bold">Road/Traffic Violations</p>
                                        <h3 class="text-dark mt-1 mb-0 fs-2 fw-bold">{{ vehicleCount }}</h3>
                                    </div> <!-- end col -->
                                </div> <!-- end row-->
                            </div> <!-- end card body -->
                            <div class="card-footer py-2 bg-light bg-opacity-50">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="text-muted ms-1 fs-12 me-1">Year</span>
                                        <span class="text-success fw-bold">{{ year }}</span>
                                    </div>
                                    <a href="#!" class="text-reset fw-semibold fs-12">More Info</a>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->
                        </Link>
                    </div> <!-- end col -->

                    <div class="col-md-6 col-xl-6">
                        <Link :href="route('unpaid')">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-md bg-light bg-opacity-50 rounded">
                                            <iconify-icon icon="streamline:ticket-1-solid"
                                                class="fs-32 text-danger avatar-title"></iconify-icon>
                                        </div>
                                    </div> <!-- end col -->
                                    <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate fw-bold">To Pay (Penalty Tickets)</p>
                                        <h3 class="text-dark mt-1 mb-0 fs-2 fw-bold">{{ unpaid }}</h3>
                                    </div> <!-- end col -->
                                </div> <!-- end row-->
                            </div> <!-- end card body -->
                            <div class="card-footer py-2 bg-light bg-opacity-50">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        ---
                                    </div>
                                    <a href="#!" class="text-reset fw-semibold fs-12">More Info</a>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->
                        </Link>
                    </div> <!-- end col -->
                    <hr class="my-3">
                    <div class="col-md-6 col-xl-6 mt-2">
                        <h5 class="fw-bold fs-5 text-muted mt-2">Data Analytics {{ year }}</h5>
                    </div>

                    <div class="col-md-6 col-xl-6 mt-2">
                        <form action="" @submit.prevent="searchYear">
                            <div class="d-flex justify-content-between aligh-items-center mb-4">
                                <select v-model="searchForm.search" id="year" class="form-select" required>
                                    <option v-for="year in years" :key="year" :value="year">
                                        {{ year }}
                                    </option>
                                </select>
                                <button
                                    class="btn btn-success ms-3 d-flex justify-content-center align-items-center shadow-lg">
                                    <iconify-icon icon="mingcute:search-fill" class="mb-2 me-2" width="24"
                                        height="24"></iconify-icon>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="card">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <apexchart type="area" :options="chartOptions" :series="series" height="500"></apexchart>
                                </div>
                                <div class="col-md-6">
                                    <div class="fw-bold text-secondary">Top Highest Violations Recorded</div>
                                    <div class="mt-4">
                                        <apexchart type="bar" :options="chartOptions2" :series="series2"
                                            height="400">
                                        </apexchart>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mt-4">
                                        <apexchart type="line" :options="chartOptions3" :series="series3" height="400"></apexchart>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                <div>
                                    <apexchart 
                                    type="pie" 
                                    :options="chartOptions4" 
                                    :series="series4" 
                                    height="400">
                                    </apexchart>
                                </div>
                                </div>


                            </div>
                        </div>
                    </div>

                </div> <!-- end row -->
            </div>

            <Footer />
        </div>


    </div>
</template>

<script>

function getMonthNumber(date) {
    return new Date(date).getMonth() + 1;
}

</script>

<style>
.tooltip-custom {
    font-family: Arial, sans-serif;
    font-size: 12px;
    padding: 10px;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.tooltip-custom strong {
    color: #333;
}

</style>