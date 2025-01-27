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

const isOverdue = (createdAt) => {
    const ticketDate = new Date(createdAt);
    const currentDate = new Date();
    const timeDiff = currentDate - ticketDate; // Time difference in milliseconds
    const daysDiff = timeDiff / (1000 * 60 * 60 * 24); // Convert milliseconds to days
    return daysDiff >= 3;
};


const successToast = ref(null);
const label = useForm({ response: null });

onMounted(() => {
    successToast.value = new bootstrap.Toast($('#success-toast'), {
        keyboard: false
    }),
    $('#unpaid-table').DataTable();

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

const paid = (id) => {
    const paidForm = useForm({ id: id, ORnumber: '' });
    SweetAlert.fire({
        icon: 'info',
        title: 'Official Receipt Number',
        input: 'text',
        inputPlaceholder: 'Enter OR Number',
        inputValidator: (value) => {
            if (!value) {
                return 'OR Number is required!';
            } else if (isNaN(value)) {
                return 'Please enter a valid number!';
            }
            return null;
        },
        showCancelButton: true,
        confirmButtonColor: '#160e45',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Proceed',
        customClass: {
            validationMessage: 'bg-transparent',
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-secondary',
        }
    }).then((result) => {
        if (result.isConfirmed) {
            paidForm.ORnumber = result.value;

            SweetAlert.fire({
                icon: 'question',
                title: 'Are you sure?',
                text: "This will confirm the penalty payment.",
                showCancelButton: true,
                confirmButtonColor: '#160e45',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Paid!'
            }).then((confirmResult) => {
                if (confirmResult.isConfirmed) {
                    paidForm.patch(route('paid-penalty'));
                    SweetAlert.close();
                }
            });
        }
    });
};

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
                                                <th>Address</th>
                                                <th>Violation</th>
                                                <th>Vehicle & Plate No.</th>
                                                <th>Driver's License</th>
                                                <th>Ticketed By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(up, index) in unpaid" :key="index">
                                                <td>{{ index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="me-4">
                                                            <iconify-icon icon="pepicons-print:person-circle" width="30" height="30"></iconify-icon>
                                                        </div>
                                                        <div class="mt-1 fw-bold">
                                                            {{ up.driverinfo.firstname }} {{ up.driverinfo.middlename }}
                                                            {{ up.driverinfo.lastname }} {{ up.driverinfo.suffix }}
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <button class="btn btn-xs btn-success mt-3" @click="paid(up.id)">
                                                            <iconify-icon icon="tdesign:money" class="me-2" width="16" height="16"></iconify-icon>
                                                            To Pay
                                                        </button>
                                                    </div>

                                                    <!-- Toast Notification -->
                                                    <!-- Basic Toast -->
                                                    <div v-if="isOverdue(up.created_at)" class="toast fade show fw-bolder bg-danger" role="alert" aria-live="assertive" aria-atomic="true"
                                                        data-bs-toggle="toast">
                                                        <div class="toast-header d-block">
                                                            <div class="float-end">
                                                                <span class="text-dark">Ticket Overdue</span>
                                                                <button type="button" class="ms-2 btn-close" data-bs-dismiss="toast"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="auth-logo">
                                                                <iconify-icon icon="solar:danger-circle-bold-duotone" height="21" class="text-danger"></iconify-icon>
                                                                <span class="ms-2">Warning</span>
                                                            </div>
                                                        </div>
                                                        <div class="toast-body fw-normal text-white">
                                                            This ticket is unpaid for over 3 days.
                                                        </div>
                                                    </div>

   
                                                </td>
                                                <td class="text-wrap">
                                                    Brgy. {{ up.driverinfo.barangay != null ? up.driverinfo.barangay.brgyDesc : '' }}
                                                    {{ up.driverinfo.municipal != null ? capitalizeWords(up.driverinfo.municipal.citymunDesc) : '' }}
                                                    {{ up.driverinfo.province != null ? capitalizeWords(up.driverinfo.province.provDesc) : '' }}
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
                                                            <tr v-for="(vl, index) in violation.filter(v => v.vehicleID === up.id)" :key="index">
                                                                <td class="p-1">
                                                                    <iconify-icon icon="lets-icons:road-finish-duotone" class="me-2 text-danger" width="20" height="20"></iconify-icon>
                                                                    <small>{{ vl.violations?.violation || 'Unknown Violation' }}</small>
                                                                </td>
                                                                <td class="p-1"><small>{{ formatOffense(vl.offense || 0) }}</small></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <div class="text-danger fw-bold fs-6">
                                                        On: {{ formatDate(up.created_at) }}
                                                    </div>
                                                </td>
                                                <td class="text-wrap">
                                                    <div>{{ up.vehicle }}</div>
                                                    <div class="fw-bold">{{ up.plateNumber }}</div>
                                                </td>
                                                <td>
                                                    <p>{{ up.IDnumber != null ? up.IDnumber : 'None' }}</p>
                                                </td>
                                                <td class="text-wrap">
                                                    <span>{{ up.ticketedBy }}</span>
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
