<script>
import { Head, Link, useForm } from '@inertiajs/vue3';

export default {
     methods: {
          logout() {
               this.$inertia.get(route('logout'));
          },

     }
}

</script>

<script setup>

import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const user = ref(null);
const profileSuccessToast = ref(null);
const profileLabel = useForm({ response: null });
const editProfileModal = ref(null);

defineProps({
     page: String
})

onMounted(() => {
     const page = usePage();
     user.value = page.props.auth;

     editProfileModal.value = new bootstrap.Modal($('#editProfileModal'), {
          keyboard: false
     });

     profileSuccessToast.value = new bootstrap.Toast($('#profile-success-toast'), {
          keyboard: false
     });
});

const editProfileForm = useForm({
     name: null,
     email: null,
     password: null,
     error: null
});


const editProfile = (name, email) => {
     editProfileModal.value.show();
     editProfileForm.name = name
     editProfileForm.email = email
};

const updateProfile = () => {
     editProfileForm.patch(route('update-profile'), {
          onFinish: () => {
               editProfileForm.error = editProfileForm.errors.error;
               editProfileForm.status = editProfileForm.errors.status;

               if (editProfileForm.status === 500) {
                    return false;
               } else {
                    setTimeout(() => {
                         editProfileModal.value.hide();
                         profileLabel.response = 'Profile updated successfully';
                         profileSuccessToast.value.show();
                    });
               }
          }
     });
};

</script>

<template>

     <!-- MODALS, TOASTS etc. -->

     <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-4">
          <div id="profile-success-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
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
                    {{ profileLabel.response }}
               </div>
          </div>
     </div>

     <div class="modal fade" id="editProfileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
          aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
               <div class="modal-content">
                    <div class="modal-header">
                         <h5 class="modal-title" id="staticBackdropLabel">Create Staff</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" @submit.prevent="updateProfile">
                         <div class="modal-body">

                              <div class="alert alert-danger" v-if="editProfileForm.error">
                                   {{ editProfileForm.error }}
                              </div>

                              <label for="" class="mb-1">Full Name</label>
                              <input type="text" class="form-control mb-3" v-model="editProfileForm.name" required>

                              <label for="" class="mb-1">Email</label>
                              <input type="email" class="form-control mb-3" v-model="editProfileForm.email" required>

                              <label for="" class="mb-1">Change Password</label>
                              <input type="password" class="form-control" v-model="editProfileForm.password">
                         </div>
                         <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="" class="btn btn-primary">Save</button>
                         </div>
                    </form>
               </div>
          </div>
     </div>

     <!-- ========== Topbar Start ========== -->
     <header class="topbar">
          <div class="container-fluid">
               <div class="navbar-header">
                    <div class="d-flex align-items-center gap-2">
                         <!-- Menu Toggle Button -->
                         <div class="topbar-item ">
                              <button type="button" class="button-toggle-menu">
                                   <iconify-icon icon="solar:hamburger-menu-broken"
                                        class="fs-24 align-middle"></iconify-icon>
                              </button>
                         </div>

                         <!-- App Search-->
                         <form class="app-search d-none d-md-block me-auto">
                              <div class="position-relative fw-bold">
                                   {{ page }}
                              </div>
                         </form>
                    </div>

                    <div class="d-flex align-items-center gap-1">

                         <!-- User -->
                         <div class="dropdown topbar-item">
                              <a type="button" class="topbar-button" id="page-header-user-dropdown"
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   <span class="d-flex align-items-center">
                                        <span class="me-2 fw-bold" v-if="user?.role === 1">{{ user?.name }}</span>
                                        <span class="me-2 fw-bold" v-if="user?.role === 2">{{ user?.staff.name }}</span>
                                        <img class="rounded-circle" width="32" src="/logo/office-man.png"
                                             alt="avatar-3">
                                   </span>
                              </a>
                              <div class="dropdown-menu">
                                   <!-- item-->
                                   <h6 class="dropdown-header fw-bold" v-if="user?.role === 1">{{ user?.name }}</h6>
                                   <h6 class="dropdown-header fw-bold" v-if="user?.role === 2">{{ user?.staff.name }}
                                   </h6>

                                   <a v-if="user?.role === 1" href="" class="dropdown-item"
                                        @click.prevent="editProfile(user?.name, user?.email)">
                                        <iconify-icon icon="solar:user-bold-duotone"
                                             class="align-middle me-2 fs-18"></iconify-icon>
                                        <span class="align-middle text-dark">My Account</span>
                                   </a>

                                   <a v-if="user?.role === 2" href="" class="dropdown-item"
                                        @click.prevent="editProfile(user?.staff.name, user?.email)">
                                        <iconify-icon icon="solar:user-bold-duotone"
                                             class="align-middle me-2 fs-18"></iconify-icon>
                                        <span class="align-middle text-dark">My Account</span>
                                   </a>

                                   <hr>
                                   <a href="" class="dropdown-item text-danger" @click.prevent="logout">
                                        <iconify-icon icon="solar:logout-3-bold-duotone"
                                             class="align-middle me-2 fs-18"></iconify-icon>
                                        <span class="align-middle text-dark">Logout</span>
                                   </a>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </header>


     <!-- ========== Topbar End ========== -->
</template>