<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Footer from '@/Layouts/Footer.vue';

defineProps({
     canResetPassword: {
          type: Boolean,
     },
     status: {
          type: String,
     },
});

const form = useForm({
     email: null,
     password: null,
     remember: false,
     error: null
});

const submit = () => {
     form.post(route('login.authenticate'), {
          onFinish: () => {
               form.error = form.errors.status
          },
     });
};

</script>

<template>

     <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
          <div class="container">
               <div class="row justify-content-center">
                    <div class="col-xl-5">
                         <div class="card auth-card shadow-lg">
                              <div class="card-body px-3 py-5">
                                   <div class=" mb-4 text-center auth-logo">
                                        <a href="#" class="logo-dark">
                                             <center>
                                                  <img src="/logo/logo.png" style="width: 160px;" alt="logo dark">
                                                  <div class="fw-bold mt-2">STMG - Road Traffic Offense IMS</div>
                                             </center>
                                        </a>
                                   </div>

                                   <h2 class="fw-bold text-uppercase text-center fs-18">Sign In</h2>
                                   <p class="text-muted text-center mt-1 mb-4">Enter your account credentials to proceed
                                   </p>

                                   <div class="px-4">

                                        <div class="alert alert-danger" v-if="form.error">
                                             {{ form.error }}
                                        </div>

                                        <form @submit.prevent="submit" class="authentication-form">

                                             <div class="mb-3">
                                                  <label class="form-label" for="example-email">Email</label>
                                                  <input v-model="form.email" type="email" id="example-email"
                                                       name="email"
                                                       class="form-control bg-light bg-opacity-50 border-light py-2"
                                                       placeholder="Enter your email" required>
                                             </div>
                                             <div class="mb-3">
                                                  <label class="form-label" for="example-password">Password</label>
                                                  <input v-model="form.password" type="password" id="example-password"
                                                       name="password"
                                                       class="form-control bg-light bg-opacity-50 border-light py-2"
                                                       placeholder="Enter your password" required>
                                             </div>
                                             <div class="mb-3">
                                                  <div class="form-check">
                                                       <input type="checkbox" class="form-check-input"
                                                            id="checkbox-signin">
                                                       <label class="form-check-label" for="checkbox-signin">Remember
                                                            me</label>
                                                  </div>
                                             </div>

                                             <div class="mb-1 text-center d-grid">
                                                  <button class="btn btn-danger py-2 fw-medium" type="submit">Sign
                                                       In</button>
                                             </div>

                                             <p class="mt-3 fw-semibold no-span"></p>

                                             <div class="text-center">
                                                  <Footer />
                                             </div>

                                        </form>
                                   </div> <!-- end col -->
                              </div> <!-- end card-body -->
                         </div> <!-- end card -->
                    </div> <!-- end col -->
               </div> <!-- end row -->
          </div>
     </div>

</template>