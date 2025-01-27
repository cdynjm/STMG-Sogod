<script setup>

import { Link, useForm } from '@inertiajs/vue3';
import { ref, reactive, onMounted } from 'vue';

import Navbar from '@/Layouts/Navbar.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
import Footer from '@/Layouts/Footer.vue';
import axios from 'axios';

defineProps({
    staff: Array,
    auth: Array,
})

const successToast = ref(null);
const label = useForm({ response: null });

const createModal = ref(null);
const editModal = ref(null);

onMounted(() => {
    createModal.value = new bootstrap.Modal($('#createModal'), {
        keyboard: false
    });
    editModal.value = new bootstrap.Modal($('#editModal'), {
        keyboard: false
    });

    successToast.value = new bootstrap.Toast($('#success-toast'), {
        keyboard: false
    });
    fetchRegions();
});

const createForm = useForm({
    firstname: null,
    lastname: null,
    middlename: null,
    suffix: null,
    position: null,
    region: null,
    province: null,
    municipal: null,
    barangay: null,
    email: null,
    contactNumber: null,
    password: null,
    error: null,
    status: null,
});

const regions = ref([]);
const provinces = ref([]);
const municipals = ref([]);
const barangays = ref([]);

// Fetch Regions when the modal is opened
const fetchRegions = async () => {
    try {
        const response = await axios.get('/admin/regions');
        regions.value = response.data;
    } catch (error) {
        console.error('Error fetching regions:', error);
    }
};

// Fetch Provinces when a Region is selected
const fetchProvinces = async () => {
    try {
        const response = await axios.get(`/admin/provinces?regCode=${createForm.region}`);
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
        const response = await axios.get(`/admin/municipals?provCode=${createForm.province}`);
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
        const response = await axios.get(`/admin/barangays?citymunCode=${createForm.municipal}`);
        barangays.value = response.data;
        createForm.barangay = null;
    } catch (error) {
        console.error('Error fetching barangays:', error);
    }
};

const fetchProvincesForEdit = async (regionCode) => {
    try {
        const response = await axios.get(`/admin/provinces?regCode=${regionCode}`);
        provinces.value = response.data;
    } catch (error) {
        console.error('Error fetching provinces for edit:', error);
    }
};

const fetchMunicipalsForEdit = async (provCode) => {
    try {
        const response = await axios.get(`/admin/municipals?provCode=${provCode}`);
        municipals.value = response.data;
    } catch (error) {
        console.error('Error fetching municipals for edit:', error);
    }
};

const fetchBarangaysForEdit = async (citymunCode) => {
    try {
        const response = await axios.get(`/admin/barangays?citymunCode=${citymunCode}`);
        barangays.value = response.data;
    } catch (error) {
        console.error('Error fetching barangays for edit:', error);
    }
};


const editForm = useForm({
    id: null,
    firstname: null,
    lastname: null,
    middlename: null,
    suffix: null,
    position: null,
    region: null,
    province: null,
    municipal: null,
    barangay: null,
    email: null,
    contactNumber: null,
    password: null,
    error: null,
    status: null,
});

const createStaff = () => {
    
    createModal.value.show();
};

const editStaff = async (
    id,
    firstname,
    middlename,
    lastname,
    suffix,
    position,
    region,
    province,
    municipal,
    barangay,
    contactNumber,
    email
) => {
    editForm.id = id;
    editForm.firstname = firstname;
    editForm.middlename = middlename;
    editForm.lastname = lastname;
    editForm.suffix = suffix;
    editForm.position = position;
    editForm.contactNumber = contactNumber;
    editForm.email = email;

    // Set region and fetch provinces
    editForm.region = region;
    await fetchProvincesForEdit(region);

    // Set province and fetch municipals
    editForm.province = province;
    await fetchMunicipalsForEdit(province);

    // Set municipal and fetch barangays
    editForm.municipal = municipal;
    await fetchBarangaysForEdit(municipal);

    // Set barangay
    editForm.barangay = barangay;

    // Show the modal
    editModal.value.show();
};


const createStaffData = () => {
    createForm.post(route('create-staff'), {
        onFinish: () => {
            createForm.error = createForm.errors.error;
            createForm.status = createForm.errors.status;

            if (createForm.status === 500) {
                return false;
            } else {
                setTimeout(() => {
                    createModal.value.hide();
                    label.response = 'Staff created successfully';
                    successToast.value.show();
                });
            }
        }
    });
};


const updateStaffData = () => {
    editForm.patch(route('update-staff'), {
        onFinish: () => {
            editForm.error = editForm.errors.error;
            editForm.status = editForm.errors.status;

            if (editForm.status === 500) {
                return false;
            } else {
                setTimeout(() => {
                    editModal.value.hide();
                    label.response = 'Staff updated successfully';
                    successToast.value.show();
                });
            }
        }
    });
};

const deleteStaff = (id) => {
    const deleteForm = useForm({ id: id });
    SweetAlert.fire({
        icon: 'warning',
        title: 'Are you sure?',
        text: "This will remove the staff permanently.",
        showCancelButton: true,
        confirmButtonColor: '#160e45',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete it!'
    }).then((result) => {
        if (result.value) {
            deleteForm.delete(route('delete-staff'));
            SweetAlert.close();
            label.response = 'Deleted Successfully'
            successToast.value.show();
        }
    })
};

function capitalizeWords(text) {
    if (!text) return '';
    return text
        .toLowerCase()
        .replace(/\b\w/g, (char) => char.toUpperCase());
}

</script>

<template>
    <div class="wrapper">
        <Navbar :page="'Staff & Personnel'" />
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

        <div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Create Staff</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" @submit.prevent="createStaffData">
                        <div class="modal-body">

                            <div class="alert alert-danger" v-if="createForm.error">
                                {{ createForm.error }}
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="" class="mb-1">First Name</label>
                                    <input type="text" class="form-control mb-3" v-model="createForm.firstname" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="mb-1">Middle Name</label>
                                    <input type="text" class="form-control mb-3" v-model="createForm.middlename">
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="mb-1">Last Name</label>
                                    <input type="text" class="form-control mb-3" v-model="createForm.lastname" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="mb-1">Suffix</label>
                                    <input type="text" class="form-control mb-3" v-model="createForm.suffix">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="mb-1">Position</label>
                                    <select name="" id="" class="form-select mb-3" v-model="createForm.position"required>
                                        <option value="STMG Enforcer">STMG Enforcer</option>
                                        <option value="Traffic Enforcer">Traffic Enforcer</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="mb-1">Contact Number</label>
                                    <input type="number" class="form-control mb-3" v-model="createForm.contactNumber" required>
                                </div>
                            </div>

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
                          
                            <label for="" class="mb-1">Email</label>
                            <input type="email" class="form-control mb-3" v-model="createForm.email" required>

                            <label for="" class="mb-1">Password</label>
                            <input type="password" class="form-control" v-model="createForm.password" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" @submit.prevent="updateStaffData">
                        <div class="modal-body">
                            <div class="alert alert-danger" v-if="editForm.error">
                                {{ editForm.error }}
                            </div>

                            <input type="hidden" class="form-control" v-model="editForm.id">

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="" class="mb-1">First Name</label>
                                    <input type="text" class="form-control mb-3" v-model="editForm.firstname" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="mb-1">Middle Name</label>
                                    <input type="text" class="form-control mb-3" v-model="editForm.middlename">
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="mb-1">Last Name</label>
                                    <input type="text" class="form-control mb-3" v-model="editForm.lastname" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="" class="mb-1">Suffix</label>
                                    <input type="text" class="form-control mb-3" v-model="editForm.suffix">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="mb-1">Position</label>
                                    <select name="" id="" class="form-select mb-3" v-model="editForm.position"required>
                                        <option value="STMG Enforcer">STMG Enforcer</option>
                                        <option value="Traffic Enforcer">Traffic Enforcer</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="mb-1">Contact Number</label>
                                    <input type="number" class="form-control mb-3" v-model="editForm.contactNumber" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="region" class="mb-1">Region</label>
                                    <select class="form-select mb-3" v-model="editForm.region" @change="fetchProvincesForEdit(editForm.region)" required>
                                        <option value="" disabled>Select a Region</option>
                                        <option v-for="region in regions" :key="region.regCode" :value="region.regCode">
                                            {{ region.regDesc }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="province" class="mb-1">Province</label>
                                    <select class="form-select mb-3" v-model="editForm.province" @change="fetchMunicipalsForEdit(editForm.province)" required>
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
                                    <select class="form-select mb-3" v-model="editForm.municipal" @change="fetchBarangaysForEdit(editForm.municipal)" required>
                                        <option value="" disabled>Select a Municipal</option>
                                        <option v-for="municipal in municipals" :key="municipal.citymunCode" :value="municipal.citymunCode">
                                            {{ municipal.citymunDesc }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="barangay" class="mb-1">Barangay</label>
                                    <select class="form-select mb-3" v-model="editForm.barangay" required>
                                        <option value="" disabled>Select a Barangay</option>
                                        <option v-for="barangay in barangays" :key="barangay.brgyCode" :value="barangay.brgyCode">
                                            {{ barangay.brgyDesc }}
                                        </option>
                                    </select>
                                </div>
                            </div>


                            <label for="" class="mb-1">Email</label>
                            <input type="email" class="form-control mb-3" v-model="editForm.email" required>

                            <label for="" class="mb-1">Change Password</label>
                            <input type="password" class="form-control" v-model="editForm.password">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="" class="btn btn-primary">Save</button>
                        </div>
                    </form>
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
                                    <h6 class="fw-bold">List of Staffs</h6>
                                    <button class="btn btn-primary shadow-lg btn-sm" @click="createStaff"> +
                                        Add</button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr class="fs-6">
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>Position</th>
                                                <th>Address</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(st, index) in staff" :key="st.id">
                                                <td>{{ index + 1 }}</td>
                                                <td>
                                                    {{ st.firstname }} {{ st.middlename }} {{ st.lastname }} {{ st.suffix ? `. ${st.suffix}` : '' }}
                                                </td>
                                                <td>{{ st.position }}</td>
                                                <td>
                                                    Brgy. {{ st.barangay != null ? st.barangay.brgyDesc : '' }}
                                                    {{ st.municipal != null ? capitalizeWords(st.municipal.citymunDesc) : '' }} 
                                                    {{ st.province != null ? capitalizeWords(st.province.provDesc) : '' }}
                                                </td>
                                                <td>
                                                    <a href="#" class="me-2" @click="editStaff(
                                                        st.id,
                                                        st.firstname,
                                                        st.middlename,
                                                        st.lastname,
                                                        st.suffix,
                                                        st.position,
                                                        st.region.regCode,
                                                        st.province.provCode,
                                                        st.municipal.citymunCode,
                                                        st.barangay.brgyCode,
                                                        st.contactNumber,
                                                        st.user.email
                                                    )"><i class="fa-solid fa-pencil"></i></a>
                                                    <a href="#" class="" @click="deleteStaff(st.id)"><i
                                                            class="fa-solid fa-trash"></i></a>
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
        confirmButton: 'btn btn-danger',
        cancelButton: 'btn btn-secondary'
    },
    buttonsStyling: false
});

</script>