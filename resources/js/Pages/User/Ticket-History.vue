<script setup>

import { Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

import Navbar from '@/Layouts/Navbar.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
import Footer from '@/Layouts/Footer.vue';

const props = defineProps({
    ticket: Object,
    auth: Array,
    violation: Object,
    year: String
})

const successToast = ref(null);
const label = useForm({ response: null });

onMounted(() => {
    successToast.value = new bootstrap.Toast($('#success-toast'), {
        keyboard: false
    }),
        $('#ticket-table').DataTable();
});

const searchForm = useForm({
    search: null,
    result: 0,
});

const currentYear = new Date().getFullYear();
const startYear = 2020;
const years = Array.from({ length: currentYear - startYear + 1 }, (_, i) => startYear + i);

const searchYear = () => {
    searchForm.get(route('user.search-year'));
}

onMounted(() => {
    searchForm.search = props.year
});

function capitalizeWords(text) {
    if (!text) return '';
    return text
        .toLowerCase()
        .replace(/\b\w/g, (char) => char.toUpperCase());
}

// Function to format offense count
function formatOffense(count) {
    const suffixes = ['th', 'st', 'nd', 'rd'];
    const mod = count % 100;
    return count + (suffixes[(mod - 20) % 10] || suffixes[mod] || suffixes[0]) + ' Offense';
}

</script>

<template>
    <div class="wrapper">
        <Navbar :page="'Ticket History'" />
        <Sidebar />

        <!-- MODALS, TOASTS etc. -->

        <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-4">
            <div id="success-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <div class="auth-logo me-auto">
                        <span class="fs-4 fw-bold">
                            Done
                        </span>
                    </div>
                    <small class="text-success fs-4">
                        <i class="fa-solid fa-circle-check"></i>
                    </small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ label.response }}
                </div>
            </div>
        </div>

        <!-- PAGE CONTENT -->

        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <form action="" @submit.prevent="searchYear">
                            <label for="" class="mb-2">Select Year</label>
                            <div class="d-flex justify-content-between aligh-items-center mb-4">

                                <select v-model="searchForm.search" id="year" class="form-select" required>
                                    <option value="">Select ...</option>
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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold">Penalty Tickets {{ year }}</h6>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="ticket-table" class="table text-nowrap">
                                        <thead>
                                            <tr class="fs-6">
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Violation</th>
                                             <!-- <th>Penalty</th> -->
                                                <th>Vehicle & Plate No.</th>
                                                <th>Driver's License</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(tc, index) in ticket">
                                                <td>{{ index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="me-4">
                                                            <iconify-icon icon="pepicons-print:person-circle" width="30"
                                                                height="30"></iconify-icon>
                                                        </div>
                                                        <div class="mt-1 fw-bold">
                                                            {{ tc.driverinfo.firstname }} {{ tc.driverinfo.middlename }} {{ tc.driverinfo.lastname }} {{ tc.driverinfo.suffix }}
                                                            <hr class="my-1">
                                                            <div class="fw-normal text-danger" v-if="tc.status === 1"><span>
                                                                <iconify-icon icon="zondicons:minus-solid" width="17" class="me-1"></iconify-icon> TO PAY</span>
                                                            </div>
                                                            <div class="fw-normal text-success" v-if="tc.status === 0">
                                                                <div>
                                                                    <iconify-icon icon="mingcute:check-2-fill" width="17" class="me-1"></iconify-icon> PAID
                                                                </div>
                                                                <small class="text-secondary fw-bold">
                                                                    {{ formatDate(tc.datePaid) }}
                                                                </small>
                                                                <div class="text-secondary">
                                                                    OR: {{ tc.ORnumber }}
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                </td>
                                                <td class="text-wrap">
                                                    Brgy. {{ tc.driverinfo.barangay != null ? tc.driverinfo.barangay.brgyDesc : '' }}
                                                    {{ tc.driverinfo.municipal != null ? capitalizeWords(tc.driverinfo.municipal.citymunDesc) : '' }} 
                                                    {{ tc.driverinfo.province != null ? capitalizeWords(tc.driverinfo.province.provDesc) : '' }}
                                                </td>
                                                <td>
                                                    <table class="table table-bordered mb-1">
                                                        <thead>
                                                            <tr>
                                                                <th class="p-1"><small>Violation</small></th>
                                                                <th class="p-1"><small>Offense</small></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(vl, index) in violation.filter(v => v.vehicleID === tc.id)"
                                                                :key="index">
                                                                <td class="p-1">
                                                                    <iconify-icon icon="lets-icons:road-finish-duotone"
                                                                        class="me-2 text-danger" width="20"
                                                                        height="20"></iconify-icon>
                                                                    <small>{{ vl.violations?.violation || 'Unknown Violation'
                                                                    }}</small>
                                                                </td>
                                                                <td class="p-1"><small>{{ formatOffense(vl.offense || 0) }}</small></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <div class="text-danger fw-bold fs-6">
                                                        On: {{ formatDate(tc.created_at) }}
                                                    </div>
                                                </td>
                                                <!--  <td>
                                                    <div class="fs-5 fw-bold">₱ {{ formatNumber(tc.violationFee) }}
                                                    </div>
                                                </td> -->
                                                <td class="text-wrap">
                                                    <div>{{ tc.vehicle }}</div>
                                                    <div class="fw-bold">{{ tc.plateNumber }}</div>
                                                </td>
                                                <td>
                                                    <p>{{ tc.IDnumber != null ? tc.IDnumber : 'None' }}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <Footer />
        </div>
    </div>
</template>

<script>

var SweetAlert = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-secondary'
    },
    buttonsStyling: false
});

function formatDate(dateString) {
    const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: 'numeric', 
        minute: 'numeric', 
        hour12: true
     };
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', options);
}

function formatNumber(value) {
    return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
}

</script>