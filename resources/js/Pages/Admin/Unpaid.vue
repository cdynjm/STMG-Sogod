<script setup>

import { Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

import Navbar from '@/Layouts/Navbar.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
import Footer from '@/Layouts/Footer.vue';

defineProps({
    unpaid: Object,
    auth: Array,
    violation: Object
})

const successToast = ref(null);
const label = useForm({ response: null });

onMounted(() => {
    successToast.value = new bootstrap.Toast($('#success-toast'), {
        keyboard: false
    }),
        $('#unpaid-table').DataTable();
});

const paid = (id) => {
    const paidForm = useForm({ id: id });
    SweetAlert.fire({
        icon: 'question',
        title: 'Are you sure?',
        text: "This will confirm the penalty payment.",
        showCancelButton: true,
        confirmButtonColor: '#160e45',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Paid!'
    }).then((result) => {
        if (result.value) {
            paidForm.patch(route('paid-penalty'));
            SweetAlert.close();
            label.response = 'Paid Successfully'
            successToast.value.show();
        }
    })
};

</script>

<template>
    <div class="wrapper">
        <Navbar :page="'Unpaid'" />
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
                                    <h6 class="fw-bold">List of Unpaid Penalty Tickets</h6>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="unpaid-table" class="table text-nowrap">
                                        <thead>
                                            <tr class="fs-6">
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>Violation</th>
                                                <th>Penalty</th>
                                                <th>Ticketed By</th>
                                                <th>Vehicle & Plate No.</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(up, index) in unpaid">
                                                <td>{{ index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="me-4">
                                                            <iconify-icon icon="pepicons-print:person-circle" width="30"
                                                                height="30"></iconify-icon>
                                                        </div>
                                                        <div class="mt-1 fw-bold">
                                                            {{ up.driverinfo.name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div v-for="(vl, index) in violation">
                                                        <div v-if="vl.vehicleID === up.id">
                                                            <iconify-icon icon="lets-icons:road-finish-duotone"
                                                                class="me-2 text-danger" width="20"
                                                                height="20"></iconify-icon> {{ vl.violations.violation
                                                            }} - ₱{{ formatNumber(vl.fee) }}
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 text-muted fs-6">
                                                        On: {{ formatDate(up.created_at) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fs-5 fw-bold">₱ {{ formatNumber(up.violationFee) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span>{{ up.ticketedBy }}</span>
                                                </td>
                                                <td>
                                                    <div>{{ up.vehicle }}</div>
                                                    <div class="fw-bold">{{ up.plateNumber }}</div>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-success" @click="paid(up.id)">
                                                        <iconify-icon icon="tdesign:money" class=" me-2" width="16"
                                                            height="16"></iconify-icon>
                                                        Paid
                                                    </button>
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