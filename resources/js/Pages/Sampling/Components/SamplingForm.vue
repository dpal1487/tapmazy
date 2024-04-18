<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import useVuelidate from "@vuelidate/core";
import { required, numeric, url } from "@vuelidate/validators";
import PrimaryButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import InputError from "@/jetstream/InputError.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import { toast } from "vue3-toastify";

import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";

export default defineComponent({
    props: ["suppliers", "project", "supplier_project", "countries", "action"],
    emits: ["submitted"],
    setup() {
        return { v$: useVuelidate() };
    },
    components: {
        Link,
        Multiselect,
        JetInput,
        JetLabel,
        InputError,
        JetValidationErrors,
        PrimaryButton,
        Loading,
    },
    validations() {
        return {
            form: {
                project_cpi: {
                    required,
                },
                complete_url: {
                    required,
                    url,
                },
                terminate_url: {
                    required,
                    url,
                },
                quotafull_url: {
                    required,
                    url,
                },
                security_terminate_url: {
                    required,
                    url,
                },
                sample_size: {
                    required,
                    numeric,
                },
                supplier: {
                    required,
                },
                notes: {
                    required,
                },
                project_status: {
                    required,
                },
            },
        };
    },
    data() {
        return {
            isLoading: false,
            fullPage: true,
            form: this.$inertia.form({
                id: this.project?.id,
                project_link_id: this.project?.id,
                project_id: this.project?.project_id,
                project_name: this.project?.project_name,
                project_cpi: this.project?.cpi,
                complete_url: this.project?.complete_url,
                terminate_url: this.project?.terminate_url,
                quotafull_url: this.project?.quotafull_url,
                security_terminate_url: this.project?.security_terminate_url,
                sample_size: this.project?.sample_size,
                supplier: this.project?.supplier?.id,
                project_status: this.project?.status || 1,
                add_more: false,
                notes: this.project?.notes || ''
            }),
        };
    },
    methods: {
        getRedirect(event) {
            this.form.complete_url = "";
            this.form.terminate_url = "";
            this.form.quotafull_url = "";
            this.form.security_terminate_url = "";
            if (event) {
                this.isLoading = true;
                axios
                    .get(this.route("sampling.redirect", event))
                    .then((response) => {
                        if (response.data.success) {
                            let data = response.data.data;
                            this.form.complete_url = data.complete_url;
                            this.form.terminate_url = data.terminate_url;
                            this.form.quotafull_url = data.quotafull_url;
                            this.form.security_terminate_url =
                                data.security_terminate_url;
                            return;
                        }
                        toast.error(response.data.message);
                    })
                    .finally(() => (this.isLoading = false));
            }
        },
        submit() {
            
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.$emit("submitted", this.form);
            }
        },
    },
    created() { },
});
</script>
<template>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="fullPage" />
    <JetValidationErrors />
    <form @submit.prevent="submit" autocomplete="off">
        <div class="row g-5">
            <div class="fv-row col-md-6 col-sm-12">
                <jet-label for="suppliers" value="Suppliers" />
                <Multiselect :canClear="false" :options="suppliers" label="display_name" valueProp="id"
                    class="form-control form-control-solid" placeholder="Select supplier" :searchable="true"
                    v-model="form.supplier" :class="v$.form.supplier.$errors.length > 0 ? 'is-invalid' : ''
                        " @input="getRedirect($event)" />
            </div>
            <div class="fv-row col-md-6 col-sm-12">
                <jet-label for="project-link" value="Project CPC/CPI" />
                <div class="input-group has-validation">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control form-control-solid" id="project-cpi" step="any"
                        placeholder="Enter project CPI/CPC" v-model="v$.form.project_cpi.$model" :class="v$.form.project_cpi.$errors.length > 0
                            ? 'is-invalid'
                            : ''
                            " />
                </div>
                <div v-for="(error, index) of v$.form.project_cpi.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-md-6 col-sm-12">
                <jet-label for="sample-size" value="Sample Size" />
                <jet-input id="sample-size" type="number" placeholder="Enter sample size"
                    v-model="v$.form.sample_size.$model" :class="v$.form.sample_size.$errors.length > 0
                        ? 'is-invalid'
                        : ''
                        " />
                <div v-for="(error, index) of v$.form.sample_size.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-md-6 col-sm-12">
                <jet-label for="complete-url" value="Complete Url" />
                <jet-input id="complete-url" type="text" v-model="v$.form.complete_url.$model" :class="v$.form.complete_url.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Enter complete url" />

                <div v-for="(error, index) of v$.form.complete_url.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-md-6 col-sm-12">
                <jet-label for="terminate-url" value="Terminate Url" />
                <jet-input id="terminate-url" type="text" v-model="v$.form.terminate_url.$model" :class="v$.form.terminate_url.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Enter terminate url" />

                <div v-for="(error, index) of v$.form.terminate_url.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-md-6 col-sm-12">
                <jet-label for="quotafull-url" value="Quotafull Url" />
                <jet-input id="quotafull-url" type="text" v-model="v$.form.quotafull_url.$model" :class="v$.form.quotafull_url.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Enter quotafull url" />

                <div v-for="(error, index) of v$.form.quotafull_url.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-md-6 col-sm-12">
                <jet-label for="security-url" value="Security Terminate Url" />
                <jet-input id="security-url" type="text" v-model="v$.form.security_terminate_url.$model" :class="v$.form.security_terminate_url.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Enter security terminate url" />

                <div v-for="(error, index) of v$.form.security_terminate_url
                    .$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-md-6 col-sm-12">
                <div class="fv-row">
                    <jet-label for="project-status" value="Project Status" />
                    <Multiselect :can-clear="false" :options="[
                        { label: 'Active', value: 1 },
                        { label: 'Inctive', value: 0 },
                    ]" label="label" valueProp="value" class="form-control form-control-solid"
                        placeholder="Select Status" v-model="form.project_status" :class="v$.form.project_status.$errors.length > 0
                            ? 'is-invalid'
                            : ''
                            " />
                    <div v-for="(error, index) of v$.form.project_status
                        .$errors" :key="index">
                        <input-error :message="error.$message" />
                    </div>
                </div>
            </div>
            <div class="fv-row col-12">
                <div class="fv-row">
                    <jet-label for="notes" value="Project Notes" />
                    <textarea id="notes" class="form-control form-control-solid" v-model="form.notes" :class="v$.form.notes.$errors.length > 0
                        ? 'is-invalid'
                        : ''
                        "></textarea>
                    <div v-for="(error, index) of v$.form.notes
                        .$errors" :key="index">
                        <input-error :message="error.$message" />
                    </div>
                </div>
            </div>
            <div class="fv-row col-12" v-if="(route().current() != 'sampling.edit')">
                <div class="form-check">

                    <input class="form-check-input" v-model="form.add_more" type="checkbox" value="" id="addMore" />
                    <label class="form-check-label" for="addMore">
                        Add More Supplier
                    </label>
                </div>
            </div>
            <slot name="action"></slot>
            <!--end::Button-->
        </div>
    </form>
</template>
