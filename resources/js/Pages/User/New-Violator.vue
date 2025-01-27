<script setup>

import { Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';

import Navbar from '@/Layouts/Navbar.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
import Footer from '@/Layouts/Footer.vue';
import { reactive } from 'vue';

defineProps({
    auth: Array,
    violations: Object
})

const successToast = ref(null);
const label = useForm({ response: null, penalty: null });

onMounted(() => {
    successToast.value = new bootstrap.Toast($('#success-toast'), {
        keyboard: false
    });

    fetchRegions();
});

const createForm = useForm({
    firstname: null,
    middlename: null,
    lastname: null,
    suffix: null,
    region: null,
    province: null,
    municipal: null,
    barangay: null,
    birthdate: null,
    age: null,
    address: null,
    gender: null,
    contactNumber: null,
    timesTicketed: null,
    IDtype: null,
    IDnumber: null,
    isDriversLicense: false,
    vehicle: null,
    plateNumber: null,
    violationFee: null,
    location: null,
    violations: [],
    violationAmounts: [], // Changed to an object for mapping by violation ID
    error: null,
    status: null,

    vehicleTypes: [
            'Taxi-sedan - taxicabs',
            'PUJ -  transport passengers/cargoes',
            'Taxi-UV - Utility Vehicles',
            'Buses - (TB) BUSES',
            'TC - motorcycles/tricycles (with sidecars) for-hire',
            'Light-PU - passenger cars for-hire',
            'Medium-PU - passenger cars for-hire',
            'SUV - SPORTS UTILITY VEHICLE',
            'TRL - trailers'
        ] // Array of vehicle types
});


const regions = ref([]);
const provinces = ref([]);
const municipals = ref([]);
const barangays = ref([]);

// Fetch Regions when the modal is opened
const fetchRegions = async () => {
    try {
        const response = await axios.get('/user/regions');
        regions.value = response.data;
    } catch (error) {
        console.error('Error fetching regions:', error);
    }
};

// Fetch Provinces when a Region is selected
const fetchProvinces = async () => {
    try {
        const response = await axios.get(`/user/provinces?regCode=${createForm.region}`);
        provinces.value = response.data;
        municipals.value = [];
        barangays.value = [];
        createForm.province = null;
        createForm.municipal = null;
        createForm.barangay = null;
    } catch (error) {
        console.error('Error fetching provinces:', error);
    }
};

// Fetch Municipals when a Province is selected
const fetchMunicipals = async () => {
    try {
        const response = await axios.get(`/user/municipals?provCode=${createForm.province}`);
        municipals.value = response.data;
        barangays.value = [];
        createForm.municipal = null;
        createForm.barangay = null;
    } catch (error) {
        console.error('Error fetching municipals:', error);
    }
};

// Fetch Barangays when a Municipal is selected
const fetchBarangays = async () => {
    try {
        const response = await axios.get(`/user/barangays?citymunCode=${createForm.municipal}`);
        barangays.value = response.data;
        createForm.barangay = null;
    } catch (error) {
        console.error('Error fetching barangays:', error);
    }
};

const createNewViolator = () => {
    createForm.timesTicketed = 1
    createForm.post(route('create-new-violator'), {
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

                  /*  createForm.name = null
                    createForm.birthdate = null
                    createForm.address = null
                    createForm.contactNumber = null
                    createForm.timesTicketed = null

                    createForm.IDtype = null
                    createForm.IDnumber = null
                    createForm.vehicle = null
                    createForm.plateNumber = null,
                        createForm.violationFee = null
                    createForm.location = null

                    createForm.violations = [],
                        createForm.violationAmounts = [] */

                });
            }
        }
    });
};

function computeAge() {
        
    const birthDate = new Date(createForm.birthdate);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    createForm.age = age; 
       
}

</script>

<template>
    <div class="wrapper">
        <Navbar :page="'New Violator'" />
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
                   <!-- <div class="mt-2">
                        Total: <span class="fs-4 fw-bold">₱ {{ formatNumber(label.penalty) }}</span>
                    </div> -->
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
                                    <h6 class="fw-bold">Create New Violator</h6>
                                </div>
                            </div>

                            <div class="card-body">
                                <form action="" @submit.prevent="createNewViolator">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <p class="mb-2">Personal & Vehicle Information</p>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="" class="mb-1">First Name</label>
                                                    <input type="text" class="form-control mb-2 text-uppercase "
                                                        v-model="createForm.firstname" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="" class="mb-1">Middle Name</label>
                                                    <input type="text" class="form-control mb-2 text-uppercase "
                                                        v-model="createForm.middlename">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="" class="mb-1">Last Name</label>
                                                    <input type="text" class="form-control mb-2 text-uppercase "
                                                        v-model="createForm.lastname" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="" class="mb-1">Suffix</label>
                                                    <input type="text" class="form-control mb-2 text-uppercase "
                                                        v-model="createForm.suffix">
                                                </div>
                                            </div>

                                            <label for="" class="my-2">ADDRESS</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="region" class="mb-1">Region</label>
                                                    <select class="form-select mb-3" v-model="createForm.region" @change="fetchProvinces" required>
                                                        <option value="" disabled>Select a Region</option>
                                                        <option v-for="region in regions" :key="region.regCode" :value="region.regCode">
                                                            {{ region.regDesc }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="province" class="mb-1">Province</label>
                                                    <select class="form-select mb-3" v-model="createForm.province" @change="fetchMunicipals" required>
                                                        <option value="" disabled>Select a Province</option>
                                                        <option v-for="province in provinces" :key="province.provCode" :value="province.provCode">
                                                            {{ province.provDesc }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="municipal" class="mb-1">Municipal</label>
                                                    <select class="form-select mb-3" v-model="createForm.municipal" @change="fetchBarangays" required>
                                                        <option value="" disabled>Select a Municipal</option>
                                                        <option v-for="municipal in municipals" :key="municipal.citymunCode" :value="municipal.citymunCode">
                                                            {{ municipal.citymunDesc }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="barangay" class="mb-1">Barangay</label>
                                                    <select class="form-select mb-3" v-model="createForm.barangay" required>
                                                        <option value="" disabled>Select a Barangay</option>
                                                        <option v-for="barangay in barangays" :key="barangay.brgyCode" :value="barangay.brgyCode">
                                                            {{ barangay.brgyDesc }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-md-6">
                                                    <label for="birthdate" class="mb-1">Birth Date</label>
                                                    <input type="date" class="form-control mb-2 text-uppercase" v-model="createForm.birthdate" @input="computeAge" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="age" class="mb-1">Age</label>
                                                    <input type="text" class="form-control mb-2" v-model="createForm.age" readonly>
                                                </div>
                                            </div>

                                            <label for="birthdate" class="mb-1">Gender</label>
                                            <select name="" id="" class="form-select mb-3" v-model="createForm.gender">
                                                <option value="1">Male</option>
                                                <option value="2">Female</option>
                                            </select>

                                            <!--   <label for="" class="mb-1">Contact Number</label>
                                            <input type="number" class="form-control mb-2 text-uppercase "
                                                v-model="createForm.contactNumber" required> -->

                                                <div class="row">
                                                    <!-- Type of Validated Identification Card -->
                                                    <div class="col-md-6">
                                                        <label for="" class="mb-1">Type of Validated Identification Card (ID)</label>
                                                        <div>
                                                            <label>
                                                                <input type="checkbox" v-model="createForm.isDriversLicense" class="form-check-input"> Driver's License
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <!-- ID Number (only visible if "Driver's License" is checked) -->
                                                    <div class="col-md-6" v-if="createForm.isDriversLicense">
                                                        <label for="" class="mb-1">ID Number</label>
                                                        <input type="text" class="form-control mb-2 text-uppercase" v-model="createForm.IDnumber" required>
                                                    </div>
                                                </div>



                                        </div>
                                        <div class="col-md-6 mb-4">

                                            <p class="mb-2">Vehicle Information</p>

                                            <label for="" class="mb-1">Type of Vehicle</label>
                                            <select class="form-select mb-2 text-uppercase" v-model="createForm.vehicle" required>
                                                <option v-for="vehicle in createForm.vehicleTypes" :key="vehicle" :value="vehicle">
                                                    {{ vehicle }}
                                                </option>
                                            </select>


                                            <label for="" class="mb-1">Plate Number</label>
                                            <input name="" id="" class="form-control mb-2 text-uppercase"
                                                v-model="createForm.plateNumber" required>

                                            <label for="" class="mb-1">Location</label>
                                            <input type="text" class="form-control mb-2 text-uppercase "
                                                v-model="createForm.location" required>

                                        </div>

                                        <div class="col-md-12">
                                            <p class="fw-bold text-danger mb-2">Driver's Violation/s</p>

                                        <div class="table-responsive">
                                            <!-- Table format for violations -->
                                            <table class="table text-wrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Violation/s</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Loop through each violation -->
                                                    <tr v-for="(vl, index) in violations" :key="vl.id">
                                                        <!-- Column for checkbox and violation label -->
                                                        <td class="d-flex align-items-center">
                                                            <input type="checkbox" :value="vl.id" class="me-2"
                                                                v-model="createForm.violations" />
                                                            <span>{{ vl.violation }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
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
