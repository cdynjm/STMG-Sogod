<script setup>

import { Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

import Navbar from '@/Layouts/Navbar.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
import Footer from '@/Layouts/Footer.vue';

const props = defineProps({
    auth: Array,
    receipt: Object
})

const successToast = ref(null);
const label = useForm({ response: null });

const receiptForm = useForm({
    treasurer: null,
    collector: null,
    error: null,
    status: null,
});

onMounted(() => {
    successToast.value = new bootstrap.Toast($('#success-toast'), {
        keyboard: false
    });

    receiptForm.treasurer = props.receipt.treasurer
    receiptForm.collector = props.receipt.collector
});

const updateReceiptInfo = () => {
    receiptForm.patch(route('update-receipt-info'), {
        onFinish: () => {
            receiptForm.error = receiptForm.errors.error;
            receiptForm.status = receiptForm.errors.status;

            if (receiptForm.status === 500) {
                return false;
            } else {
                setTimeout(() => {
                    label.response = 'Receipt info updated successfully';
                    successToast.value.show();
                });
            }
        }
    });
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

        

        <!-- PAGE CONTENT -->

        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Receipt Info</h6>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="updateReceiptInfo">
                                    <label for="">Provincial/Municipal Treasurer</label>
                                    <input type="text" class="form-control mb-2" v-model="receiptForm.treasurer" required>

                                    <label for="">Deputy Collector</label>
                                    <input type="text" class="form-control mb-3" v-model="receiptForm.collector" required>

                                    <button class="btn btn-sm btn-success shadow-lg">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
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