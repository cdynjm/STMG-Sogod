<script setup>

import { Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

import Navbar from '@/Layouts/Navbar.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
import Footer from '@/Layouts/Footer.vue';

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
});

const createForm = useForm({
    name: null,
    position: null,
    address: null,
    email: null,
    contactNumber: null,
    password: null,
    error: null,
    status: null,
});

const editForm = useForm({
    id: null,
    name: null,
    position: null,
    address: null,
    email: null,
    contactNumber: null,
    password: null,
    error: null,
    status: null,
});

const createStaff = () => {
    createModal.value.show();
};

const editStaff = (id, name, position, address, contactNumber, email) => {
    editModal.value.show();
    editForm.id = id
    editForm.name = name
    editForm.position = position
    editForm.address = address
    editForm.contactNumber = contactNumber
    editForm.email = email
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
            <div class="modal-dialog">
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

                            <label for="" class="mb-1">Full Name</label>
                            <input type="text" class="form-control mb-3" v-model="createForm.name" required>

                            <label for="" class="mb-1">Position</label>
                            <input type="text" class="form-control mb-3" v-model="createForm.position" required>

                            <label for="" class="mb-1">Contact Number</label>
                            <input type="number" class="form-control mb-3" v-model="createForm.contactNumber" required>

                            <label for="" class="mb-1">Address</label>
                            <input type="text" class="form-control mb-3" v-model="createForm.address" required>

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
            <div class="modal-dialog">
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

                            <label for="" class="mb-1">Full Name</label>
                            <input type="text" class="form-control mb-3" v-model="editForm.name" required>

                            <label for="" class="mb-1">Position</label>
                            <input type="text" class="form-control mb-3" v-model="editForm.position" required>

                            <label for="" class="mb-1">Contact Number</label>
                            <input type="number" class="form-control mb-3" v-model="editForm.contactNumber" required>

                            <label for="" class="mb-1">Address</label>
                            <input type="text" class="form-control mb-3" v-model="editForm.address" required>

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
                                                    {{ st.name }}
                                                </td>
                                                <td>{{ st.position }}</td>
                                                <td>{{ st.address }}</td>
                                                <td>
                                                    <a href="#" class="me-2" @click="editStaff(
                                                        st.id,
                                                        st.name,
                                                        st.position,
                                                        st.address,
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