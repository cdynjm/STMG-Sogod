<script setup>
import { ref, onMounted } from 'vue';
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
    year: String
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

const series2 = ref([]); // Data for the violations
const labels = ref([]);  // Labels for each violation

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
        categories: labels.value, // Assign labels to the x-axis
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

const populateMonthData = () => {
    if (props.vehicle && Array.isArray(props.vehicle)) {
        props.vehicle.forEach(v => {
            const monthIndex = new Date(v.created_at).getMonth();
            month[monthIndex]++;
        });
    }
};

const populateViolationData = () => {
    if (props.violationList && Array.isArray(props.violationList)) {
        props.violationList.forEach(listViolation => {
            const count = props.violation.filter(v => v.violation === listViolation.id).length;
            series2.value.push(count);
            labels.value.push(listViolation.violation);
        });
        // Ensure the chart updates reactively by assigning the series data properly
        series2.value = [{
            name: 'Violations',
            data: [...series2.value]
        }];
    }
};

onMounted(() => {
    populateMonthData();
    populateViolationData();
    searchForm.search = props.year
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
                                    <a href="#!" class="text-reset fw-semibold fs-12">Total Violations</a>
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
                                        <p class="text-muted mb-0 text-truncate fw-bold">Unpaid Penalty Tickets</p>
                                        <h3 class="text-dark mt-1 mb-0 fs-2 fw-bold">{{ unpaid }}</h3>
                                    </div> <!-- end col -->
                                </div> <!-- end row-->
                            </div> <!-- end card body -->
                            <div class="card-footer py-2 bg-light bg-opacity-50">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        ---
                                    </div>
                                    <a href="#!" class="text-reset fw-semibold fs-12">Total Violations</a>
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
                                    <apexchart type="area" :options="chartOptions" :series="series"></apexchart>
                                </div>
                                <div class="col-md-6">
                                    <div class="mt-4">
                                        <apexchart type="bar" :options="chartOptions2" :series="series2"
                                            height="350">
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