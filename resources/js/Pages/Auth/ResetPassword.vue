<script>

import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

export default {
  props: {
    token: String,
    email: String,
  },
  setup(props) {
    const form = useForm({
      token: props.token,
      email: props.email || '',
      password: '',
      password_confirmation: '',
      error: null,
      success: null
    });

    const submit = () => {
      form.post(route('password.update'), {
        onError: (errors) => {
          form.error = 'Password did not match.';
        }
      });
    };

    return { form, submit };
  },
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
                                                <img src="/logo/stmg-2.png" class="me-2" style="width: 160px;" alt="logo dark">
                                                <div class="fw-bold mt-2">STMG-Road Traffic Offense IMS</div>
                                           </center>
                                      </a>
                                 </div>

                                 <h2 class="fw-bold text-uppercase text-center fs-18">Reset Password</h2>
                                 <p class="text-muted text-center mt-1 mb-4">Create new password</p>

                                 <div class="px-4">


                                  <div class="alert alert-danger" v-if="form.error">
                                    {{ form.error }}
                                  </div>

                                  <form @submit.prevent="submit">
                                    <input type="hidden" v-model="form.token" />
                                    <div>
                                      <label for="email">Email</label>
                                      <input type="email" class="form-control form-control-sm mb-3" v-model="form.email" readonly />
                                    </div>
                                    <div>
                                      <label for="password">Password</label>
                                      <input type="password" class="form-control form-control-sm mb-3" v-model="form.password" required />
                                    </div>
                                    <div>
                                      <label for="password_confirmation">Confirm Password</label>
                                      <input type="password" class="form-control form-control-sm mb-3" v-model="form.password_confirmation"
                                        required />
                                    </div>
                                    <div>
                                      <button type="submit" class="btn btn-sm btn-primary">Reset Password</button>
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
