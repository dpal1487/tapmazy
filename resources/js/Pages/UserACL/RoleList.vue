<script>

import { defineComponent } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/inertia-vue3';
import UserRoleForm from './Components/UserRoleForm.vue';
import ListPermissions from './Permissions/ListPermissions.vue';
import utils from '../../utils';
export default defineComponent({
    props: ['permissions', 'roles', 'menus'],
    data() {
        return {
            isEdit: false,
            role_id: '',
            showModal: false,
            title: "User Role",
        }
    },
    components: {
        AppLayout,
        Head,
        Link,
        UserRoleForm,
        ListPermissions
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
        async confirmDelete(id, index) {
            this.isLoading = true;
            await utils.deleteIndexDialog(route('role.destroy', id), this.roles.data, index);
            this.isLoading = false;
        },
    }
})
</script>
<template>
    <AppLayout :title="title">

        <Head :title="title" />
        <UserRoleForm v-if="showModal" :show="showModal" :isEdit="isEdit" @hidemodal="toggleModal(false)"
            :permissions="permissions?.data" :role="role_id" :menus="menus?.data" />
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <span class="text-muted">Roles</span>
            </li>
        </template>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">
            <div class="col-md-6" v-for="(role, index) in roles.data" :key="index">
                <div class="card card-flush h-md-100">
                    <div class="card-header">
                        <div class="card-title text-uppercase">
                            <h2>{{ role.name }}</h2>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="fw-bold text-gray-600 mb-5">Total users with this role: {{ role?.users?.length }}</div>
                        <ListPermissions :permissions="role?.permissions" />
                    </div>
                    <div class="card-footer flex-wrap pt-0">
                        <Link :href="`role/${role.id}`" class="btn btn-light btn-active-primary my-1 me-2">View Role</Link>
                        <button type="button" class="btn btn-light btn-active-light-primary my-1 me-2"
                            @click="toggleModal(true, role.id)">Edit Role</button>
                        <button type="button" class="btn btn-light btn-active-danger my-1"
                            @click="confirmDelete(role.id, index)">Delete Role</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-md-100">
                    <div class="card-body d-flex flex-center">
                        <button type="button" class="btn btn-clear d-flex flex-column flex-center" @click=toggleModal(true)>
                            <img src="/assets/media/illustrations/sketchy-1/4.png" alt="" class="mw-100 mh-150px mb-7" />
                            <div class="fw-bold fs-3 text-gray-600 text-hover-primary">Add New Role</div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
