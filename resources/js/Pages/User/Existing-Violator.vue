<script setup>

import { Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

import Navbar from '@/Layouts/Navbar.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
import Footer from '@/Layouts/Footer.vue';

defineProps({
    auth: Array,
    violations: Object,
    driver: Object
})

const successToast = ref(null);
const label = useForm({ response: null, penalty: null });

onMounted(() => {
    successToast.value = new bootstrap.Toast($('#success-toast'), {
        keyboard: false
    });
});

const createForm = useForm({
    id: null,
    name: null,
    age: null,
    address: null,
    contactNumber: null,
    timesTicketed: null,

    IDtype: null,
    IDnumber: null,
    vehicle: null,
    violationFee: null,
    location: null,

    violations: [],

    error: null,
    status: null

});

const createNewViolation = (id, name, timesTicketed) => {

    createForm.id = id
    createForm.name = name
    createForm.timesTicketed = timesTicketed

    createForm.post(route('create-new-violation'), {
        onFinish: () => {
            createForm.error = createForm.errors.error;
            createForm.status = createForm.errors.status;
            label.penalty = createForm.errors.penalty;

            if (createForm.status === 500) {
                return false;
            } else {
                setTimeout(() => {
                    label.response = 'Violation submitted successfully';
                    successToast.value.show();

                    createForm.age = null
                    createForm.address = null
                    createForm.contactNumber = null

                    createForm.IDtype = null
                    createForm.IDnumber = null
                    createForm.vehicle = null
                    createForm.violationFee = null
                    createForm.location = null

                    createForm.violations = []

                });
            }
        }
    });
};

</script>

<template>
    <div class="wrapper">
        <Navbar :page="'Existing Driver'" />
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
                    <div class="mt-2">
                        Total: <span class="fs-4 fw-bold">₱ {{ formatNumber(label.penalty) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold">Create New Violation</h6>
                                </div>
                            </div>

                            <div class="card-body">
                                <form action=""
                                    @submit.prevent="createNewViolation(driver.id, driver.name, driver.timesTicketed)">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <p class="mb-2">Personal & Vehicle Information</p>

                                            <label for="" class="mb-1">Name</label>
                                            <input type="text" class="form-control mb-2 text-uppercase"
                                                :value="driver.name" readonly>

                                            <label for="" class="mb-1">Age</label>
                                            <input type="number" class="form-control mb-2 text-uppercase"
                                                v-model="createForm.age" required>

                                            <label for="" class="mb-1">Address</label>
                                            <input type="text" class="form-control mb-2 text-uppercase"
                                                v-model="createForm.address" required>

                                            <label for="" class="mb-1">Contact Number</label>
                                            <input type="number" class="form-control mb-2 text-uppercase"
                                                v-model="createForm.contactNumber" required>

                                            <label for="" class="mb-1">Type of Validated Identification Card
                                                (ID)</label>
                                            <input type="text" class="form-control mb-2 text-uppercase"
                                                v-model="createForm.IDtype" required>

                                            <label for="" class="mb-1">ID Number</label>
                                            <input type="text" class="form-control mb-2 text-uppercase"
                                                v-model="createForm.IDnumber" required>

                                            <label for="" class="mb-1">Vehicle Information</label>
                                            <textarea name="" id="" class="form-control text-uppercase" rows="3"
                                                v-model="createForm.vehicle" required></textarea>

                                        </div>
                                        <div class="col-md-6 mb-2">

                                            <p class="mb-2">Road Violation Information</p>

                                            <label for="" class="mb-1">Number of times he/she been ticketed for traffic
                                                violations</label>
                                            <input type="number" min="1" class="form-control text-uppercase mb-4"
                                                :value="driver.timesTicketed + 1" readonly>

                                            <div class="mb-4">
                                                <p class="fw-bold text-danger mb-2">Driver's Violation/s</p>

                                                <div v-for="(vl, index) in violations" class="mb-2">
                                                    <input type="checkbox" :value="vl.id" class="me-2"
                                                        v-model="createForm.violations">
                                                    <label for="">{{ vl.violation }} - ₱{{ vl.fee }}</label>
                                                </div>

                                            </div>

                                            <label for="" class="mb-1">Location</label>
                                            <input type="text" class="form-control mb-2 text-uppercase"
                                                v-model="createForm.location" required>

                                        </div>

                                        <div class="col-md-12">
                                            <div class="text-center">
                                                <button class="btn btn-success shadow-lg mt-2">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', options);
}

function formatNumber(value) {
    return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
}

</script>
