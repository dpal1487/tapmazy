<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import InputError from "@/jetstream/InputError.vue";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { toast } from "vue3-toastify";
import DescriptionForm from "./Components/DescriptionForm.vue";
import SubmitButton from "../../Components/SubmitButton.vue";

export default defineComponent({
    props: ['plan', 'currencies'],
    setup() {
        return { v$: useVuelidate() };
    },
    validations() {
        return {
            form: {
                name: {
                    required,
                },
                sort_description: {
                    required,
                },
                price: {
                    required,
                },
                currency_value: {
                    
                },
                interval: {
                    required,
                },
                status: {
                },
                coupon: {

                },
                percent_off: {

                },
                allowed_projects: {
                    required,
                },
                allowed_invoices: {
                    required,
                },
                allowed_users: {
                    required,
                },
                allowed_clients: {
                    required,
                },
                allowed_suppliers: {
                    required,
                }
            },
        };
    },
    data() {
        return {
            rowCount: 1,
            items: 1,
            isEdit: false,
            processing: false,
            title: 'Plan',
            description: this.plan?.data?.description,
            form: this.$inertia.form({
                id: this.plan?.data?.id || '',
                name: this.plan?.data?.plan_name || '',
                sort_description: this.plan?.data?.sort_description || '',
                status: this.plan?.data?.status || 0,
                interval: this.plan?.data?.plan_type || '',
                price: this.plan?.data?.plan_price || '',
                currency_value: this.plan?.data?.currency_value || '',
                coupon: this.plan?.data?.coupon_name || '',
                percent_off: this.plan?.data?.percent_off || '',
                allowed_projects: this.plan?.data?.allowed_projects || '',
                allowed_invoices: this.plan?.data?.allowed_invoices || '',
                allowed_users: this.plan?.data?.allowed_users || '',
                allowed_clients: this.plan?.data?.allowed_clients || '',
                allowed_suppliers: this.plan?.data?.allowed_suppliers || '',
                items: this.plan?.data ? JSON.parse(this.plan?.data.description) : [{
                }],
            }),
            status: [
                { value: '1', label: 'Active' },
                { value: '0', label: 'Inactive' },
            ],
            intervals: [
                { label: 'Day', value: 'day' },
                { label: 'Week', value: 'week' },
                { label: 'Month', value: 'month' },
                { label: 'Year', value: 'year' },
            ]
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        Multiselect,
        JetInput,
        JetLabel,
        InputError,
        VueDatePicker,
        DescriptionForm,
        SubmitButton
    },
    methods: {
        submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.processing = true
                this.form.transform((data) => ({
                    ...data
                }))
                    .post(route().current() == 'plan.create' ? this.route("plan.store") : this.route("plan.update",),
                        {
                            onSuccess: (data) => {
                                toast.success(this.$page.props.jetstream.flash.message);
                                this.isEdit = false;
                                this.isAdd = false;
                            },
                            onError: (data) => {
                                toast.error(data.message);
                            },
                        })
            }
        },
        addItemForm(rowCount) {
            for (var i = 0; i < rowCount; i++) {
                this.form.items.push({
                });
            }
        },
        removeItemForm(index) {
            if (this.form.items.length > 0) {
                console.log(index)
                this.form.items.splice(index, 1)
            }
        },
    },

    created() {
        if (route().current() == 'plan.edit') {
            this.isEdit = true;
        }
    },
});
</script>
<template >
    <AppLayout :title="title">

        <Head :title="title" />
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/plans" class="text-muted text-hover-primary">Plans</Link>
            </li>
        </template>
        <div class="d-flex flex-column flex-lg-row flex-column-fluid justify-content-center">
            <div class="col-12">
                <form @submit.prevent="submit()" class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <div class="row">
                        <div class="col-lg-8 col-12 mb-5">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>General </h2>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex gap-5 flex-column flex-sm-row mb-5 col-12">
                                        <div class="w-100 w-sm-50">
                                            <jet-label for="name" value="Name" />
                                            <jet-input id="name" type="text" v-model="v$.form.name.$model" :class="v$.form.name.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Name" />
                                            <div v-for="(error, index) of v$.form.name.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                        <div class="w-100 w-sm-50">
                                            <jet-label for="sort_description" value="Sort Description" />
                                            <jet-input id="sort_description" type="text"
                                                v-model="v$.form.sort_description.$model" :class="v$.form.sort_description.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " placeholder="Sort Description" />
                                            <div v-for="(error, index) of v$.form.sort_description.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>


                                    </div>


                                    <div class="d-flex gap-5 flex-column flex-sm-row mb-5 col-12">
                                        <div class="w-100 w-sm-50">
                                            <jet-label for="price" value="Price" />
                                            <jet-input id="price" type="text" v-model="v$.form.price.$model" :class="v$.form.price.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Price" />
                                            <div v-for="(error, index) of v$.form.price.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                        <div class="w-100 w-sm-50">
                                            <jet-label for="allowed_suppliers" value="Allowed suppliers" />
                                            <jet-input id="allowed_suppliers" type="text"
                                                v-model="v$.form.allowed_suppliers.$model" :class="v$.form.allowed_suppliers.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " placeholder="Allowed suppliers" />
                                            <div v-for="(error, index) of v$.form.allowed_suppliers.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-5 flex-column flex-sm-row mb-5 col-12">
                                        <div class="w-100 w-sm-50">
                                            <jet-label for="allowed_projects" value="Allowed projects" />
                                            <jet-input id="allowed_projects" type="text"
                                                v-model="v$.form.allowed_projects.$model" :class="v$.form.allowed_projects.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " placeholder="Allowed projects" />
                                            <div v-for="(error, index) of v$.form.allowed_projects.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                        <div class="w-100 w-sm-50">
                                            <jet-label for="allowed_invoices" value="Allowed invoices" />
                                            <jet-input id="allowed_invoices" type="text"
                                                v-model="v$.form.allowed_invoices.$model" :class="v$.form.allowed_invoices.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " placeholder="Allowed invoices" />
                                            <div v-for="(error, index) of v$.form.allowed_invoices.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>


                                    </div>
                                    <div class="d-flex gap-5 flex-column flex-sm-row mb-5 col-12">
                                        <div class="w-100 w-sm-50">
                                            <jet-label for="allowed_users" value="Allowed users" />
                                            <jet-input id="allowed_users" type="text" v-model="v$.form.allowed_users.$model"
                                                :class="v$.form.allowed_users.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " placeholder="Allowed users" />
                                            <div v-for="(error, index) of v$.form.allowed_users.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                        <div class="w-100 w-sm-50">
                                            <jet-label for="allowed_clients" value="Allowed clients" />
                                            <jet-input id="allowed_clients" type="text"
                                                v-model="v$.form.allowed_clients.$model" :class="v$.form.allowed_clients.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " placeholder="Allowed clients" />
                                            <div v-for="(error, index) of v$.form.allowed_clients.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>


                                    </div>

                                    <div class="d-flex gap-5 flex-column flex-sm-row mb-5 col-12">
                                        <div class="w-100 w-sm-50">
                                            <jet-label for="interval" value="Interval" />
                                            <Multiselect id="interval" :options="intervals" :searchable="true"
                                                :can-clear="false" label="label" valueProp="value"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="Choose One" v-model="v$.form.interval.$model" track-by="label"
                                                :class="v$.form.interval.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " />
                                            <div v-for="(error, index) of v$.form.interval.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                        <div class="w-100 w-sm-50">
                                            <jet-label for="status" value="Status" />
                                            <Multiselect :options="status" :searchable="true" :can-clear="false"
                                                label="label" valueProp="value"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="Choose One" v-model="v$.form.status.$model" track-by="name"
                                                :class="v$.form.status.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " />
                                            <div v-for="(error, index) of v$.form.status.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-5 flex-column flex-sm-row mb-5 col-12">
                                        <div class="w-100 w-sm-50">
                                            <jet-label for="coupon" value="Coupon" />
                                            <jet-input id="coupon" type="text" v-model="v$.form.coupon.$model" :class="v$.form.coupon.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Coupon" />
                                            <div v-for="(error, index) of v$.form.coupon.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                        <div class="w-100 w-sm-50">
                                            <jet-label for="percent_off" value="Percentage Off" />
                                            <jet-input id="percent_off" type="text" v-model="v$.form.percent_off.$model"
                                                :class="v$.form.percent_off.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " placeholder="Percentage Off" />
                                            <div v-for="(error, index) of v$.form.percent_off.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h4> Descriptions</h4>
                                    </div>
                                </div>
                                <div class="card-body pt-1">
                                    <div class="d-flex flex-column flex-sm-row">
                                        <table class="table gs-0 m-0 fw-bold text-gray-700">
                                            <thead>
                                                <tr class="border-bottom fs-7 fw-bold text-gray-700 text-uppercase">
                                                    <th>Meta</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <DescriptionForm :form="form" @removeSingle="removeItemForm" :plan="plan" />
                                            </tbody>
                                            <tfoot>
                                                <tr
                                                    class="border-top border-top-dashed align-top fs-6 fw-bold text-gray-700">
                                                    <th class="text-primary pt-2">
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            @click="addItemForm(this.rowCount)">Add
                                                            more</button>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <SubmitButton link="/plans" :routeProp="route().current()" :processing="processing" />
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
