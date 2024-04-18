<script>
import { defineComponent } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, Head } from '@inertiajs/inertia-vue3';
import Pagination from "../../Jetstream/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import UserRoleForm from './Components/UserRoleForm.vue';

export default defineComponent({
    props: ['role', 'users', 'permissions'],

    data() {
        return {
            isEdit: false,
            role_id: '',
            showModal: false,
            title: 'Role View',
            form: {}
        }
    },
    components: {
        AppLayout,
        Link,
        Head,
        Pagination,
        UserRoleForm
    },
    methods: {
        toggleModal(value, role) {
            if (value, role) {
                this.isEdit = true;
                this.showModal = true;
                this.role_id = role;
            }
            else if (value) {
                this.showModal = true;
                this.isEdit = false;
                this.role_id = '';

            }
            else {
                this.showModal = false;
            }
        },
        search() {
            Inertia.get(
                "/role/" + this.role.data.id,
                this.form,
            );
        },
    }

})
</script>

<template>
    <Head :title="title" />

    <AppLayout :title="title">
        <UserRoleForm v-if="showModal" :show="showModal" :isEdit="isEdit" @hidemodal="toggleModal(false)" :role="role_id"
            page="role_view" />

        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/role" class="text-muted text-hover-primary">Roles</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                {{ title }}
            </li>
        </template>
        <div class="app-content flex-column-fluid">
            <div class="row g-5">
                <div class="com-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h2 class="mb-0">Role Name : {{ role.data.name }}</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex flex-column text-gray-600" v-if="role.data.permissions != 0">
                                <div class="d-flex align-items-center py-2" v-for="permission in role.data?.permissions ">
                                    <span class="bullet bg-primary me-3"></span>
                                    {{ permission?.description }}
                                </div>
                            </div>
                            <div class="d-flex flex-column text-gray-600" v-else>
                                <div class="d-flex align-items-center mt-5">
                                    <span class="fs-5 text-gray-700 fw-bold">No permission assign for
                                        {{ role.data.name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer pt-5">
                            <button type="button" class="btn btn-light btn-active-primary"
                                @click="toggleModal(true, role.data.id)">Edit Role</button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header justify-content-between align-items-center g-5 ">
                            <div class="card-title mt-2">
                                <h2 class="d-flex align-items-center ">Users Assigned
                                    <span class="text-gray-600 fs-5 ms-2">({{ users?.meta?.total }})</span>
                                </h2>
                            </div>
                            <form @submit.prevent="search">
                                <div class="d-flex align-items-center  position-relative  my-5"
                                    data-kt-view-roles-table-toolbar="base">
                                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                                transform="rotate(45 17.0365 15.1223)" fill="currentColor">
                                            </rect>
                                            <path
                                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                    <input type="text" v-model="form.q"
                                        class="form-control form-control-solid w-250px ps-15" placeholder="Search Users">
                                    <button type="submit" class="btn btn-primary btn-sm h-40px mx-5" style="height: 42px;">
                                        Search
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0 no-footer">
                                    <thead>
                                        <tr class="text-start text-muted text-gray-700 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-150px">User</th>
                                            <th class="min-w-150px">Joined Date</th>
                                            <th class="text-end min-w-150px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-500">
                                        <tr v-for="user in users.data">
                                            <td class="d-flex align-items-center">
                                                <div class="d-flex flex-column">
                                                    <Link :href="`/user/${user.id}`"
                                                        class="text-gray-800 fw-bold text-hover-primary mb-1">
                                                    {{ user?.full_name }}
                                                    </Link>
                                                    <span>{{ user?.email }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                {{ user?.join_date }}
                                            </td>
                                            <td class="text-end min-w-150px">
                                                <Link class="btn btn-sm btn-light menu-dropdown"
                                                    :href="`/user/${user.id}/edit`"><i class="bi bi-pencil me-2"></i>Edit
                                                </Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <Pagination :links="users" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
