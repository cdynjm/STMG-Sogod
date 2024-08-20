<script setup>

import { Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

import Navbar from '@/Layouts/Navbar.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
import Footer from '@/Layouts/Footer.vue';

defineProps({
    violation: Array,
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
    violation: null,
    fee: null,
    error: null,
    status: null,
});

const editForm = useForm({
    id: null,
    violation: null,
    fee: null,
    error: null,
    status: null,
});

const createViolation = () => {
    createModal.value.show();
};

const editViolation = (id, violation, fee) => {
    editModal.value.show();
    editForm.id = id
    editForm.violation = violation,
        editForm.fee = fee
};

const createViolationData = () => {
    createForm.post(route('create-violation'), {
        onFinish: () => {
            setTimeout(() => {
                createModal.value.hide();
                label.response = 'Violation created successfully';
                successToast.value.show();
            });
        }
    });
};


const updateViolationData = () => {
    editForm.patch(route('update-violation'), {
        onFinish: () => {
            setTimeout(() => {
                editModal.value.hide();
                label.response = 'Violation updated successfully';
                successToast.value.show();
            });
        }
    });
};

const deleteViolation = (id) => {
    const deleteForm = useForm({ id: id });
    SweetAlert.fire({
        icon: 'warning',
        title: 'Are you sure?',
        text: "This will remove the violation permanently.",
        showCancelButton: true,
        confirmButtonColor: '#160e45',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete it!'
    }).then((result) => {
        if (result.value) {
            deleteForm.delete(route('delete-violation'));
            SweetAlert.close();
            label.response = 'Deleted Successfully'
            successToast.value.show();
        }
    })
};

</script>

<template>
    <div class="wrapper">
        <Navbar :page="'Violations'" />
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
                        <h5 class="modal-title" id="staticBackdropLabel">Create Violation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" @submit.prevent="createViolationData">
                        <div class="modal-body">

                            <div class="alert alert-danger" v-if="createForm.error">
                                {{ createForm.error }}
                            </div>

                            <label for="" class="mb-1">Violation Name</label>
                            <input type="text" class="form-control mb-3" v-model="createForm.violation" required>

                            <label for="" class="mb-1">Violation Fee</label>
                            <input type="number" class="form-control mb-3" step="0.01" min="1" v-model="createForm.fee"
                                required>

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
                        <h5 class="modal-title" id="staticBackdropLabel">Edit Violation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" @submit.prevent="updateViolationData">
                        <div class="modal-body">
                            <div class="alert alert-danger" v-if="editForm.error">
                                {{ editForm.error }}
                            </div>

                            <input type="hidden" class="form-control" v-model="editForm.id">

                            <label for="" class="mb-1">Violation Name</label>
                            <input type="text" class="form-control mb-3" v-model="editForm.violation" required>

                            <label for="" class="mb-1">Violation Fee</label>
                            <input type="number" class="form-control mb-3" step="0.01" min="1" v-model="editForm.fee"
                                required>

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
                                    <h6 class="fw-bold">List of Violations</h6>
                                    <button class="btn btn-primary shadow-lg btn-sm" @click="createViolation"> +
                                        Add</button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr class="fs-6">
                                                <th>No.</th>
                                                <th>Violations</th>
                                                <th>Violation Fee</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(vl, index) in violation" :key="vl.id">
                                                <td>{{ index + 1 }}</td>
                                                <td>{{ vl.violation }}</td>
                                                <td>₱ {{ formatNumber(vl.fee) }}</td>
                                                <td>
                                                    <a href="#" class="me-2" @click="editViolation(
                                                        vl.id,
                                                        vl.violation,
                                                        vl.fee
                                                    )"><i class="fa-solid fa-pencil"></i></a>
                                                    <a href="#" class="" @click="deleteViolation(vl.id)"><i
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

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', options);
}

function formatNumber(value) {
    return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
}

</script>