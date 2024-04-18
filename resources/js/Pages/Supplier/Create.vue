<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import InputError from "@/jetstream/InputError.vue";
import useVuelidate from "@vuelidate/core";
import { required, email, url } from "@vuelidate/validators";
import { toast } from 'vue3-toastify';
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import PlanWarning from "../../Components/PlanWarning.vue",
export default defineComponent({
    props: ['supplier', 'countries' , 'allowed_suppliers' , 'suplliers'],
    setup() {
        return { v$: useVuelidate() };
    },
    validations() {
        return {
            form: {
                country: {
                    required
                },
                supplier_name: {
                    required,
                },
                display_name: {
                    required,
                },
                email_address: {
                    required, email,
                },
                website: {
                },
                skype_profile: {
                },
                linkedin_profile: {
                },
                description: {
                },
                status: {
                    required,
                },
                complete_url: {
                    required, url
                },
                terminate_url: {
                    required, url
                },
                quotafull_url: {
                    required, url
                },
                security_terminate_url: {
                    required, url
                }
            },
        };
    },
    data() {
        return {
            isEdit: false,
            processing: false,
            form: this.$inertia.form({
                id: this.supplier?.data?.id || '',
                supplier_name: this.supplier?.data?.supplier_name || '',
                display_name: this.supplier?.data?.display_name || '',
                email_address: this.supplier?.data?.email_address || '',
                website: this.supplier?.data?.website || '',
                skype_profile: this.supplier?.data?.skype_profile || '',
                linkedin_profile: this.supplier?.data?.linkedin_profile || '',
                description: this.supplier?.data?.description || '',
                country: this.supplier?.data?.country?.id || '',
                status: this.supplier?.data?.status || 0,
                complete_url: this.supplier?.data?.supplier_redirect?.complete_url || '',
                terminate_url: this.supplier?.data?.supplier_redirect?.terminate_url || '',
                quotafull_url: this.supplier?.data?.supplier_redirect?.quotafull_url || '',
                security_terminate_url: this.supplier?.data?.supplier_redirect?.security_terminate_url || '',
            }),
            status: [
                { name: 'Active', id: '1' },
                { name: 'Inactive', id: '0' },
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
        JetValidationErrors,
        PlanWarning
    },
    methods: {
        submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.form.transform((data) => ({
                    ...data,
                }))
                    .post(route().current() == 'supplier.create' ? this.route("supplier.store") : this.route('supplier.update', this.form.id), {
                        onSuccess: (data) => {
                            toast.success(this.$page.props.jetstream.flash.message);
                            this.isEdit = false;
                        },
                        onError: (data) => {
                            if (data.message) {
                                toast.error(data.message);
                            }
                        },
                    })
            }
        },
    },
    created() {
        if (route().current() == 'supplier.edit') {
            this.isEdit = true;
        }
    }
});
</script>
<template>
    <Head :title="isEdit ? 'Edit Supplier' : `Add New supplier`" />
    <AppLayout :title="isEdit ? 'Edit supplier' : `Add New supplier`">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/suppliers" class="text-muted text-hover-primary">Suppliers</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                Supplier Form
            </li>
        </template>
        <div class="d-flex flex-column flex-lg-row flex-column-fluid justify-content-center">
            <div class="col-12">
                <div v-if="suppliers > allowed_suppliers?.data?.allowed_suppliers">
                    <PlanWarning :data="allowed_suppliers?.data" />
                </div>
                <div v-else>
                    <JetValidationErrors />
                    <form @submit.prevent="submit()" class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>General</h2>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-5 col-md-12">
                                    <div class="fv-row col-md-6">
                                        <jet-label for="country" value="Country" />
                                        <Multiselect :options="countries.data" :can-clear="false" label="display_name"
                                            valueProp="id" class="form-control form-control-lg form-control-solid"
                                            placeholder="Select Country" v-model="form.country" track-by="display_name"
                                            :searchable="true" :class="v$.form.country.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " />
                                        <div v-for="(error, index) of v$.form.country.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <jet-label for="supplier_name" value="Supplier Name" />
                                        <jet-input id="supplier_name" type="supplier_name"
                                            v-model="v$.form.supplier_name.$model" :class="v$.form.supplier_name.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Supplier Name" />
                                        <div v-for="(error, index) of v$.form.supplier_name.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <jet-label for="display_name" value="Display Name" />
                                        <jet-input id="display_name" type="text" v-model="v$.form.display_name.$model"
                                            :class="v$.form.display_name.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Enter supplier Display Name" />
                                        <div v-for="(error, index) of v$.form.display_name.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <jet-label for="email_address" value="Email Address" />
                                        <jet-input id="email_address" type="text" v-model="v$.form.email_address.$model"
                                            :class="v$.form.email_address.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Email Address" />
                                        <div v-for="(error, index) of v$.form.email_address.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <jet-label for="website" value="Website" />
                                        <jet-input id="website" type="text" v-model="v$.form.website.$model" :class="v$.form.website.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " placeholder="Website" />
                                        <div v-for="(error, index) of v$.form.website.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <jet-label for="skype_profile" value="Skype Profile" />
                                        <jet-input id="skype_profile" type="text" v-model="v$.form.skype_profile.$model"
                                            :class="v$.form.skype_profile.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Skype Profile" />
                                        <div v-for="(error, index) of v$.form.skype_profile.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <jet-label for="linkedin_profile" value="LinkedIn Profile" />
                                        <jet-input id="linkedin_profile" type="text"
                                            v-model="v$.form.linkedin_profile.$model" :class="v$.form.linkedin_profile.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="LinkedIn Profile" />
                                        <div v-for="(error, index) of v$.form.linkedin_profile.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <jet-label for="status" value="Status" />
                                        <Multiselect :can-clear="false" :options="status" label="name" valueProp="id"
                                            class="form-control form-control-lg form-control-solid" placeholder="Select One"
                                            v-model="form.status" track-by="name" :class="v$.form.status.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " />
                                        <div v-for="(error, index) of v$.form.status.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-12">
                                        <jet-label for="description" value="Description" />
                                        <textarea rows="2" class="form-control form-control-solid"
                                            v-model="v$.form.description.$model" :class="v$.form.description.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " id="project-description"
                                            placeholder="Type important message here..."></textarea>

                                        <div v-for="(error, index) of v$.form.description.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Supplier Redirects</h2>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-5 col-md-12">
                                    <div class="fv-row col-md-6">
                                        <jet-label for="complete_url" value="Complete URL" />
                                        <jet-input id="complete_url" type="text" v-model="v$.form.complete_url.$model"
                                            :class="v$.form.complete_url.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Enter supplier Complete URL" />
                                        <div v-for="(error, index) of v$.form.complete_url.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <jet-label for="terminate_url" value="Terminate URL" />
                                        <jet-input id="terminate_url" type="text" v-model="v$.form.terminate_url.$model"
                                            :class="v$.form.terminate_url.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Terminate URL" />
                                        <div v-for="(error, index) of v$.form.terminate_url.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <jet-label for="quotafull_url" value="Quotafull URL" />
                                        <jet-input id="quotafull_url" type="text" v-model="v$.form.quotafull_url.$model"
                                            :class="v$.form.quotafull_url.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Quotafull URL" />
                                        <div v-for="(error, index) of v$.form.quotafull_url.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <jet-label for="security_terminate_url" value="Security Terminate URL" />
                                        <jet-input id="security_terminate_url" type="text"
                                            v-model="v$.form.security_terminate_url.$model" :class="v$.form.security_terminate_url.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Security Terminate URL" />
                                        <div v-for="(error, index) of v$.form.security_terminate_url.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row text-align-center">
                            <div class="col-12 gap-5">
                                <div class="d-flex justify-content-end gap-2">
                                    <Link href="/suppliers"
                                        class="btn btn-outline-secondary align-items-center justify-content-center">
                                    Discard
                                    </Link>


                                    <button type="submit" class="btn btn-primary"
                                        :class="{ 'text-white-50': form.processing }">
                                        <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>

                                        <span v-if="route().current() == 'supplier.edit'">Save Changes</span>
                                        <span v-if="route().current() == 'supplier.create'">Save</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
