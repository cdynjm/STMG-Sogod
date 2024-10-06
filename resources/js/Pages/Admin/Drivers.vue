<script setup>

import { Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

import Navbar from '@/Layouts/Navbar.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
import Footer from '@/Layouts/Footer.vue';

defineProps({
    drivers: Object,
    auth: Array,
})

const successToast = ref(null);
const label = useForm({ response: null });

onMounted(() => {
    successToast.value = new bootstrap.Toast($('#success-toast'), {
        keyboard: false
    }),
    $('#drivers-table').DataTable();
});

</script>

<template>
    <div class="wrapper">
        <Navbar :page="'Driver Records'" />
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
                                    <h6 class="fw-bold">Violator Records</h6>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="drivers-table" class="table text-nowrap">
                                        <thead>
                                            <tr class="fs-6">
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Contact Number</th>
                                                <th>Times Ticketed</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(dr, index) in drivers">
                                                <td>{{ index + 1 }}</td>
                                                <td>
                                                    <Link :href="route('drivers-info', { id: dr.id })">
                                                    <div class="d-flex">
                                                        <div class="me-4">
                                                            <iconify-icon icon="pepicons-print:person-circle" width="30"
                                                                height="30"></iconify-icon>
                                                        </div>
                                                        <div class="mt-1 fw-bold">
                                                            {{ dr.name }}
                                                        </div>
                                                    </div>
                                                    </Link>
                                                </td>
                                                <td>
                                                    {{ dr.address }}
                                                </td>
                                                <td>
                                                    {{ dr.contactNumber }}
                                                </td>
                                                <td>
                                                    <span class="fs-5 fw-bolder">{{ dr.timesTicketed }}</span>
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
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', options);
}

function formatNumber(value) {
    return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
}

</script>