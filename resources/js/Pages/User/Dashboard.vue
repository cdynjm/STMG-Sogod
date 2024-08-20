<script setup>

import { Link, useForm, usePage } from '@inertiajs/vue3';

import Navbar from '@/Layouts/Navbar.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
import Footer from '@/Layouts/Footer.vue';

defineProps({ auth: Array, drivers: Array, result: Number })

const searchForm = useForm({
    search: null,
    result: 0,
});

const searchExistingViolator = () => {
    searchForm.get(route('search-exising-violator'));
};

</script>

<template>
    <div class="wrapper">
        <Navbar :page="'Dashboard'" />
        <Sidebar />

        <div class="page-content">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form action="" @submit.prevent="searchExistingViolator">
                            <label for="" class="mb-2">Search Existing Driver</label>
                            <div class="d-flex justify-content-between aligh-items-center mb-4">

                                <input type="text" class="form-control" placeholder="Search..."
                                    v-model="searchForm.search" required>
                                <button
                                    class="btn btn-success ms-3 d-flex justify-content-center align-items-center shadow-lg">
                                    <iconify-icon icon="mingcute:search-fill" class="mb-2 me-2" width="24"
                                        height="24"></iconify-icon>
                                </button>

                            </div>
                        </form>
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold text-muted">Search Results will display here</h6>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Contact Number</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(dr, index) in drivers" :key="dr.id">
                                                <td>{{ index + 1 }}</td>
                                                <td>
                                                    <Link :href="route('existing-violator', { id: dr.id })">
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
                                                <td>{{ dr.address }}</td>
                                                <td>{{ dr.contactNumber }}</td>
                                            </tr>
                                            <tr v-if="result === 0">
                                                <td colspan="4" class="text-center text-danger">No Data Found</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                    <p class="mt-3 fw-semibold no-span mb-3">or</p>

                    <div class="col-md-12">
                        <Link :href="route('new-violator')" class="btn btn-danger w-100 shadow-lg">
                        <iconify-icon icon="solar:danger-triangle-line-duotone" class="mb-sm-1 me-2" width="24"
                            height="24"></iconify-icon>
                        Create New Violator
                        </Link>
                    </div>
                </div>

            </div>

            <Footer />
        </div>


    </div>
</template>
