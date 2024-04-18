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

export default defineComponent({
    setup() {
        return { v$: useVuelidate() };
    },
    props: ["countries", "project", "states", "cities"],
    emits: ["submitted"],
    components: {
        Link,
        Multiselect,
        JetInput,
        JetLabel,
        InputError,
        JetValidationErrors,
        PrimaryButton,
    },
    validations() {
        return {
            form: {
                project_name: {
                    required,
                },
                cpi: {
                    required,
                },
                loi: {
                    required,
                    numeric,
                },
                ir: {
                    required,
                    numeric,
                },
                sample_size: {
                    required,
                    numeric,
                },
                project_link: {
                    required,
                    url,
                },
                project_country: {
                    required,
                    numeric,
                },
            },
        };
    },
    data() {
        return {
            form: this.$inertia.form({
                id: this.project?.id,
                project_id: this.project?.project_id,
                project_name: this.project?.project_name,
                cpi: this.project?.cpi,
                loi: this.project?.loi,
                ir: this.project?.ir,
                sample_size: this.project?.sample_size,
                project_link: this.project?.project_link,
                project_country: this.project?.country?.id,
                project_state: this.project?.state,
                project_city: this.project?.city,
                target: this.project?.target,
                status: this.project?.status || 1,
                add_more: false
            }),
        };
    },
    methods: {
        submit(action) {
            this.form.action = action;
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.$emit("submitted", this.form);
            }
        },
        getStates(id) {
            const state = this.states?.data?.find(state => state.id == id);
            this.form.project_state = state?.name;
            axios.get('/project/state', {
                params: {
                    country_id: id
                }
            }).then((response) => {
                this.form.project_state = [];
                this.form.project_city = [];
                if (response.data?.states?.length > 0) {
                    console.log(response.data.states)
                    this.states = response?.data?.states;
                    this.cities = response?.data?.cities;
                }
                else {
                    this.states = []
                    this.cities = []
                }
            });
        },
    },
    created() { },
});
</script>
<template>
    <JetValidationErrors />
    <form @submit.prevent="submit" autocomplete="off">
        <div class="row g-5">
            <div class="fv-row col-md-6 col-sm-12">
                <jet-label for="project-name" value="Project Name" />
                <jet-input id="project-name" readonly type="text" v-model="v$.form.project_name.$model" :class="v$.form.project_name.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Enter project name" />
                <div v-for="(error, index) of v$.form.project_name.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-md-6 col-sm-12">
                <jet-label for="project-name" value="Project Country" />
                <Multiselect :canClear="false" :options="countries" @change='getStates' label="label" valueProp="id"
                    class="form-control form-control-solid" placeholder="Select country" :searchable="true"
                    v-model="form.project_country" :class="v$.form.project_country.$errors.length > 0
                        ? 'is-invalid'
                        : ''
                        " />
            </div>
            <div class="col-md-6 col-sm-12">
                <jet-label for="project-state" value="Project State" />
                <Multiselect :close-on-select="false" mode="tags" :can-clear="false" id="project-state" :options="states"
                    label="name" valueProp="name" class="form-control form-control-solid" placeholder="Select state"
                    :searchable="true" v-model="form.project_state" :disabled="!form.project_country" />

            </div>
            <div class="col-md-6 col-sm-12">
                <jet-label for="project-city" value="Project City" />
                <Multiselect :can-clear="false" :close-on-select="false" :options="cities" label="name" valueProp="name"
                    :create-option="true" mode="tags" class="form-control form-control-solid" placeholder="Select city"
                    :searchable="true" v-model="form.project_city" :disabled="!form.project_state" />
            </div>
            <div class="fv-row col-12">
                <jet-label for="project-link" value="Project Link" />
                <jet-input id="project-link" type="text" v-model="v$.form.project_link.$model" :class="v$.form.project_link.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Enter project link" />
                <div v-for="(error, index) of v$.form.project_link.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>

                <div class="form-text">
                    Capture unique user id as a (<b>RespondentID</b>)
                </div>
            </div>
            <div class="fv-row col-md-6 col-sm-12">
                <jet-label for="project-link" value="Project CPC/CPI" />
                <div class="input-group has-validation">
                    <span class="input-group-text">$</span>
                    <input type="number" step="any" class="form-control form-control-solid" id="project-cpi"
                        placeholder="Enter project CPI/CPC" v-model="v$.form.cpi.$model" :class="v$.form.cpi.$errors.length > 0
                            ? 'is-invalid'
                            : ''
                            " />
                </div>
                <div v-for="(error, index) of v$.form.cpi.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-md-6 col-sm-12">
                <jet-label for="project-loi" value="Project LOI" />
                <jet-input id="project-loi" type="number" v-model="v$.form.loi.$model" :class="v$.form.loi.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Enter project LOI" />
                <div v-for="(error, index) of v$.form.loi.$errors" :key="index">
                    <input-error :message="error.$message" />
                </div>
            </div>
            <div class="fv-row col-md-6 col-sm-12">
                <jet-label for="project-ir" value="Project Incidence Rate (IR)" />
                <jet-input id="project-ir" type="number" v-model="v$.form.ir.$model" :class="v$.form.ir.$errors.length > 0
                    ? 'is-invalid'
                    : ''
                    " placeholder="Enter incidence rate" />
                <div v-for="(error, index) of v$.form.ir.$errors" :key="index">
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
            <div class="fv-row col-12">
                <jet-label for="project-target" value="Project target" />
                <textarea rows="5" class="form-control form-control-solid" v-model="form.target" id="project-target"
                    placeholder="Type important message here..."></textarea>
            </div>
            <div class="fv-row col-12">
                <div class="d-flex justify-content-between">
                    <div class="d-flex">
                        <div class="form-check form-check-custom form-check-solid me-3">
                            <input class="form-check-input" type="radio" value="1" id="active" v-model="form.status" />
                            <label class="form-check-label" for="active">
                                Active
                            </label>
                        </div>
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" value="0" id="inactive" v-model="form.status" />
                            <label class="form-check-label" for="inactive">
                                Inactive
                            </label>
                        </div>
                    </div>
                    <div class="form-check" v-if="route().current() == 'mapping.create'">
                        <input class="form-check-input" type="checkbox" id="new-link" v-model="form.add_more" />
                        <label class="form-check-label" for="new-link">
                            Add More Project Link
                        </label>
                    </div>
                </div>
            </div>
            <slot name="action"></slot>
        </div>
    </form>
</template>
