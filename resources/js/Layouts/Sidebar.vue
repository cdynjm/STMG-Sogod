<script>
import { Head, Link, useForm } from '@inertiajs/vue3';

function checkWindowSize() {
  if ($(window).width() < 576) {
    $("html").attr("data-menu-size", "hidden");
  } else {
    $("html").removeAttr("data-menu-size");
    $(".page-content").show();
  }
}

$(document).ready(function () {
  checkWindowSize();
  $(window).resize(checkWindowSize);

  $(document).on("click", ".button-toggle-menu", function () {
    const menuSize = $("html").attr("data-menu-size");
    if ($(window).width() < 576) {
      if (menuSize === "hidden") {
        $("html").removeAttr("data-menu-size");
        $(".page-content").hide(200);
      } else {
        $("html").attr("data-menu-size", "hidden");
        $(".page-content").show(200);
      }
    } else {
      if (menuSize === "hidden") {
        $("html").removeAttr("data-menu-size");
      } else {
        $("html").attr("data-menu-size", "hidden");
      }
    }
  });
});

export default {
  methods: {

    dashboard() {
      this.$inertia.get(route('dashboard'));
      checkWindowSize();
      $(window).resize(checkWindowSize);
    },
    sample() {
      this.$inertia.get(route('staff'));
      checkWindowSize();
      $(window).resize(checkWindowSize);
    },
    violation() {
      this.$inertia.get(route('violation'));
      checkWindowSize();
      $(window).resize(checkWindowSize);
    },
    drivers() {
      this.$inertia.get(route('drivers'));
      checkWindowSize();
      $(window).resize(checkWindowSize);
    },
    ticketHistory() {
      this.$inertia.get(route('user.ticket-history'));
      checkWindowSize();
      $(window).resize(checkWindowSize);
    },


    isDashboardActive() {
      return this.$page.component === "Admin/Dashboard" || this.$page.component === "User/Dashboard";
    },
    isStaffActive() {
      return this.$page.component === "Admin/Staff";
    },
    isViolationActive() {
      return this.$page.component === "Admin/Violation";
    },
    isDriversActive() {
      return this.$page.component === "Admin/Drivers";
    },
    isTicketHistoryActive() {
      return this.$page.component === "User/Ticket-History";
    },
  },
};
</script>

<script setup>

import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const user = ref(null);

onMounted(() => {
  const page = usePage();
  user.value = page.props.auth;
});

</script>

<template>
  <!-- ========== App Menu Start ========== -->
  <div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box mt-3 mb-3 d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <a href="#" class="logo-dark d-flex align-items-center">
          <img src="/logo/stmg-2.png" class="logo-xl" alt="logo dark" style="width: 150px;" />
        </a>
      </div>
    </div>

    <div class="scrollbar" data-simplebar>
      <ul class="navbar-nav" id="navbar-nav">
        <hr>
        <li class="menu-title">Menu</li>
        <div v-if="user?.role === 1">
          <li class="nav-item">
            <a href="" @click.prevent="dashboard" :class="['nav-link', isDashboardActive() ? 'active' : '']"
              data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
              <span class="nav-icon">
                <iconify-icon icon="solar:chart-bold-duotone" width="24" height="24"></iconify-icon>
              </span>
              <span class="nav-text" style="font-size: 14px;"> Dashboard </span>
            </a>
          </li>

          <li class="nav-item">
            <a href="" @click.prevent="sample" :class="['nav-link', isStaffActive() ? 'active' : '']"
              data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSample">
              <span class="nav-icon">
                <iconify-icon icon="solar:users-group-rounded-bold-duotone" width="24" height="24"></iconify-icon>
              </span>
              <span class="nav-text" style="font-size: 14px;"> Staff </span>
            </a>

          </li>

          <li class="nav-item">
            <a href="" @click.prevent="violation" :class="['nav-link', isViolationActive() ? 'active' : '']"
              data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSample">
              <span class="nav-icon">
                <iconify-icon icon="solar:pin-list-bold-duotone" width="24" height="24"></iconify-icon>
              </span>
              <span class="nav-text" style="font-size: 14px;"> Violations </span>
            </a>
          </li>

          <li class="nav-item">
            <a href="" @click.prevent="drivers" :class="['nav-link', isDriversActive() ? 'active' : '']"
              data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSample">
              <span class="nav-icon">
                <iconify-icon icon="fluent:chart-person-20-filled" width="24" height="24"></iconify-icon>
              </span>
              <span class="nav-text" style="font-size: 14px;"> Driver Records </span>
            </a>
          </li>
        </div>

        <div v-if="user?.role === 2">
          <li class="nav-item">
            <a href="" @click.prevent="dashboard" :class="['nav-link', isDashboardActive() ? 'active' : '']"
              data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
              <span class="nav-icon">
                <iconify-icon icon="solar:screencast-bold-duotone" width="24" height="24"></iconify-icon>
              </span>
              <span class="nav-text" style="font-size: 14px;"> Dashboard </span>
            </a>
          </li>

          <li class="nav-item">
            <a href="" @click.prevent="ticketHistory" :class="['nav-link', isTicketHistoryActive() ? 'active' : '']"
              data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
              <span class="nav-icon">
                <iconify-icon icon="streamline:ticket-1-solid" width="20" height="20"></iconify-icon>
              </span>
              <span class="nav-text" style="font-size: 14px;"> Ticket History </span>
            </a>
          </li>
        </div>
      </ul>
    </div>
  </div>
  <!-- ========== App Menu End ========== -->
</template>
