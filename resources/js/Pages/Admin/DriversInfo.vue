<script setup>

import { Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

import Navbar from '@/Layouts/Navbar.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
import Footer from '@/Layouts/Footer.vue';

defineProps({
    driverInfo: Object,
    driverVehicle: Object,
    driverViolation: Object,
    auth: Array,
})

const successToast = ref(null);
const label = useForm({ response: null });

onMounted(() => {
    successToast.value = new bootstrap.Toast($('#success-toast'), {
        keyboard: false
    })
});

</script>

<template>
    <div class="wrapper">
        <Navbar :page="'Driver Information'" />
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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold fs-3">
                                        <span class="me-2">
                                            <iconify-icon icon="pepicons-print:person-circle" width="30"
                                                height="30"></iconify-icon>
                                        </span>
                                        {{ driverInfo.name }}
                                    </h6>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <p>Contact Number: {{ driverInfo.contactNumber }}</p>
                                    <p>Address: {{ driverInfo.address }}</p>
                                    <p>Age: {{ driverInfo.age }}</p>
                                    <p class="fw-bold mt-2"><iconify-icon icon="streamline:ticket-1-solid" class="me-2"
                                            width="18" height="18"></iconify-icon> Penalty Records History: </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12" v-for="(dv, index) in driverVehicle">
                        <div class="card">
                            <div class="card-body">
                                <!-- Logo & title -->
                                <div class="clearfix">
                                    <div class="float-sm-end">
                                        <address class="mt-3">
                                            {{ dv.location }}<br>
                                            <small>STATUS: </small>
                                            <span class="text-success fw-bold" v-if="dv.status === 0">PAID</span>
                                            <span class="text-danger fw-bold" v-if="dv.status === 1">UNPAID</span>
                                        </address>
                                    </div>
                                    <div class="float-sm-start">
                                        <h5 class="card-title mb-2">Penalty/Violation Ticket</h5>
                                        <p>{{ formatDate(dv.created_at) }}</p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mt-2">Vehicle Information: <span class="fw-bold">{{ dv.vehicle
                                                }}</span></p>
                                        <p>ID Information: <span class="fw-bold">{{ dv.IDtype }} - {{ dv.IDnumber
                                                }}</span></p>
                                    </div> <!-- end col -->
                                </div>
                                <!-- end row -->

                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive table-borderless text-nowrap mt-3 table-centered">
                                            <table class="table mb-0">
                                                <thead class="bg-light bg-opacity-50">
                                                    <tr>
                                                        <th class="border-0 py-2">Violations</th>
                                                        <th class="border-0 py-2">Fee</th>
                                                    </tr>
                                                </thead> <!-- end thead -->
                                                <tbody>
                                                    <tr v-for="(dvl, index) in driverViolation">
                                                        <td v-if="dvl.vehicleID === dv.id">{{ dvl.violations.violation
                                                            }}</td>
                                                        <td v-if="dvl.vehicleID === dv.id">₱{{ formatNumber(dvl.fee) }}
                                                        </td>
                                                    </tr>
                                                </tbody> <!-- end tbody -->
                                            </table> <!-- end table -->
                                        </div> <!-- end table responsive -->
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row mt-3">
                                    <div class="col-sm-7">
                                        <div class="clearfix pt-xl-3 pt-0">
                                            <h6 class="fw-normal text-muted">Issued/Ticketed By: </h6>
                                            <h6 class="fs-16 fw-bold my-1">{{ dv.ticketedBy }}</h6>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="float-end">
                                            <span class="float-end">Total: <span class="fs-4 fw-bolder ms-2">₱ {{
                                                    formatNumber(dv.violationFee) }}</span></span>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                            </div> <!-- end card body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->
                </div> <!-- end row -->
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
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', options);
}

function formatNumber(value) {
    return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
}

</script>