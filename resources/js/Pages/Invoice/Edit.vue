<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import InputError from "@/jetstream/InputError.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import axios from 'axios';
import { toast } from "vue3-toastify";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";

export default defineComponent({
    props: ['clients', 'currencies', 'status', 'invoice'],
    setup() {
        return { v$: useVuelidate() };
    },
    validations() {
        return {
            form: {
                client: {
                    required,
                },
                from_address: {
                    required,
                },
                to_address: {
                    required,
                },
                issue_date: {
                    required,
                },
                due_date: {
                    required
                },
                notes: {
                },
                status: {
                    required,
                },
                type: {
                    required,
                },
                conversion_rate:
                {
                    required,
                },
                currency:
                {
                    required,
                },
                tax_rate: {
                    required
                },
            },
        };
    },
    data() {
        return {
            title: "Edit Invoice",
            isEdit: false,
            isLoading: false,
            items: 1,
            rowCount: 1,
            form: this.$inertia.form({
                from_address: "121B, F/F Block A, Indira Park, Uttam Nagar,New Delhi,Delhi, India - 110059",
                to_address: this.invoice.data.to_address || "",
                client: this.invoice.data.client?.id || "",
                type: "Invoice",
                issue_date: this.invoice.data.issue_date || "",
                due_date: this.invoice.data.due_date || "",
                interval_date: this.invoice.data.due_date,
                selectedDays: this.invoice.data?.selected_days || 0,
                conversion_rate: this.invoice.data.conversion_rate || "",
                total_amount: this.invoice.data.total_amount || "",
                tax_amount: this.invoice.data.tax_amount || "",
                notes: this.invoice.data.notes || "",
                status: this.invoice.data.status || "",
                currency: this.invoice.data.currency_id || this.currencies.data[1],
                tax_rate: this.invoice.data.tax_rate || "",
                items: this.invoice.data.items || [{
                    project_name: '',
                    cpi: '',
                    quantity: '',
                }],
            }),
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
        Loading,
        JetValidationErrors
    },
    methods: {
        submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.form
                    .transform((data) => ({
                        ...data,
                    }))
                    .post(this.route("invoice.update", this.invoice.data.id), {
                        onSuccess: (data) => {
                            toast.success(this.$page.props.jetstream.flash.message);
                        },
                        onError: (data) => {
                            if (data.message) {
                                toast.error(data.message)
                            }

                        },
                    });
            }
        },
        addItemForm(rowCount) {
            for (var i = 0; i < rowCount; i++) {
                this.form.items.push({
                    project_name: '',
                    cpi: '',
                    quantity: '',
                    price: ''
                });
            }
        },
        removeItem(index) {
            if (this.form.items.length > 0) {
                this.form.items.splice(index, 1)
            }
        },
        handler() {
            var total = 0;
            for (var i = 0; i < this.form.items.length; i++) {
                total += this.form.items[i].cpi * this.form.items[i].quantity;
            }
            this.form.total_amount = total;
            var final_amount = this.form.conversion_rate * this.form.total_amount;
            if (this.form.tax_rate) {
                this.form.tax_amount = parseFloat(final_amount / 118 * this.form.tax_rate).toFixed(2);
            }
        },
        getAddress(event) {
            this.form.to_address = ''
            this.isLoading = true;
            axios.get(`/client/${event}/address`).then((response) => {
                if (response.data.success) {
                    var address = response.data.data
                    this.form.to_address = `${address.address}, ${address.state}, ${address.city}, ${address.country.name} - ${address.pincode}`;
                    return;
                }
                toast.error(response.data.message);
            }).finally(() => {
                this.isLoading = false;
            })
        },
        updateDate() {


            if (this.form.issue_date !== "") {
                if (this.form.selectedDays == 0) {
                    this.form.due_date = this.invoice.data.due_date;
                } else {
                    const currentDate = new Date(this.form.issue_date);
                    const selectedDays = parseInt(this.form.selectedDays);
                    // Check if currentDate is a valid date
                    if (!isNaN(currentDate.getTime())) {
                        const year = currentDate.getFullYear();
                        const month = currentDate.getMonth();
                        let daysInMonth;
                        // Calculate days in the month
                        if (month === 1) { // February
                            daysInMonth = new Date(year, month + 1, 0).getDate();
                        } else {
                            daysInMonth = new Date(year, month + 1, 0).getDate();
                        }
                        // Check for leap year and adjust February's days
                        if (month === 2 && daysInMonth === 28 && (year % 4 === 0 && (year % 100 !== 0 || year % 400 === 0))) {
                            daysInMonth = 29; // Leap year
                        }
                        currentDate.setDate(currentDate.getDate() + selectedDays);
                        // Check if the new day exceeds the month's days
                        if (currentDate.getDate() > daysInMonth) {
                            currentDate.setDate(daysInMonth);
                        }
                        // Format the new due date
                        const formattedDate = currentDate.toISOString().split('T')[0];
                        console.log("Formatted Date: ", formattedDate);

                        const originalDueDate = new Date(this.invoice.data.due_date);
                        const differenceInMilliseconds = currentDate - originalDueDate;
                        const differenceInDays = Math.abs(Math.floor(differenceInMilliseconds / (24 * 60 * 60 * 1000)));

                        console.log("Difference in days: ", differenceInDays);
                        this.form.difference_date = differenceInDays;
                        this.form.due_date = formattedDate;
                    } else {
                        // Handle invalid date if necessary
                        console.error("Invalid date format");
                    }
                }
            }
        },
    },
    created() {

    }
});

</script>

<template>
    <Head :title="title" />
    <AppLayout :title="title">
        <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/invoices" class="text-muted text-hover-primary">Invoices</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <span class="text-muted text-hover-primary">Create Invoice</span>
            </li>
        </template>
        <div class="app-content flex-column-fluid">
            <JetValidationErrors />

            <form @submit.prevent="submit()" class="d-flex flex-column flex-lg-row">
                <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                                <div class="d-flex flex-column align-items-center flex-xxl-row gap-2">
                                    <div class="d-flex flex-center flex-equal fw-row text-nowrap order-1 order-xxl-2 me-4">
                                        <span class="fs-2x fw-bold text-gray-800">{{ form.type }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed"></div>
                                <div class="mb-0">
                                    <div class="row d-flex align-items-start">
                                        <div class="col-6">
                                            <div class="form-control form-control-solid"><b>From :</b> A.R Solution</div>
                                        </div>
                                        <div class="col-6">
                                            <Multiselect :can-clear="false" :options="clients.data" label="display_name"
                                                valueProp="id" class="form-control form-control-lg form-control-solid"
                                                :searchable="true" v-model="v$.form.client.$model" :class="v$.form.client.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " track-by="label" placeholder="To Client"
                                                @change="getAddress($event)" />
                                            <div v-for="(error, index) of v$.form.client.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="d-flex mt-5 flex-column gap-2 text-gray-600 fs-5">
                                                <textarea rows="3" class="form-control form-control-solid"
                                                    v-model="v$.form.from_address.$model" :class="v$.form.from_address.$errors.length > 0
                                                        ? 'is-invalid'
                                                        : ''
                                                        " readonly></textarea>
                                                <div v-for="(error, index) of v$.form.from_address.$errors" :key="index">
                                                    <input-error :message="error.$message" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <textarea rows="3" class="form-control from-control-lg form-control-solid mt-5"
                                                placeholder="To Address..." v-model="v$.form.to_address.$model" :class="v$.form.to_address.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    ">
                                            </textarea>
                                            <div v-for="(error, index) of v$.form.to_address.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive mb-10 mt-10 border-top">
                                        <table class="table g-5 gs-0 mb-0 fw-bold text-gray-700" data-kt-element="items">
                                            <thead>
                                                <tr
                                                    class="border-bottom fs-7 fw-bold text-gray-700 text-uppercase text-center">
                                                    <th>Project Name</th>
                                                    <th>CPI</th>
                                                    <th>Quantity</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in form.items" :key="index"
                                                    class="border-bottom border-bottom-dashed">
                                                    <td>
                                                        <input type="text" class="form-control form-control-solid mb-2"
                                                            v-model="item.project_name" placeholder="Project name" />
                                                    </td>
                                                    <td> <input type="text" class="form-control form-control-solid"
                                                            v-model="item.cpi" :placeholder="`${form.currency.symbol}0.00`"
                                                            @keyup="handler()" />
                                                    </td>
                                                    <td class="ps-0">
                                                        <input class="form-control form-control-solid" type="text"
                                                            v-model="item.quantity" placeholder="1" @keyup="handler()" />
                                                    </td>
                                                    <td class="pt-8 text-center text-nowrap">
                                                        {{ form.currency.symbol }}{{ item.quantity *
                                                            item.cpi
                                                        }}
                                                    </td>
                                                    <td class="pt-5 text-end">
                                                        <button :disabled="index < 1" type="button"
                                                            class="btn btn-sm btn-icon btn-active-color-primary"
                                                            @click="removeItem(index)">
                                                            <span class="svg-icon svg-icon-3">
                                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                                                                        fill="currentColor" />
                                                                    <path opacity="0.5"
                                                                        d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"
                                                                        fill="currentColor" />
                                                                    <path opacity="0.5"
                                                                        d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z"
                                                                        fill="currentColor" />
                                                                </svg>
                                                            </span>
                                                            <!--end::Svg Icon-->
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr
                                                    class="border-top border-top-dashed align-top fs-6 fw-bold text-gray-700">
                                                    <th colspan="2" class="text-primary">
                                                        <div class="d-flex">
                                                            <input type="number"
                                                                class="form-control form-control-solid w-100px"
                                                                v-model="rowCount" min="1">
                                                            <button type="button" class="btn btn-primary btn-sm ms-5"
                                                                @click="addItemForm(this.rowCount)">Add More</button>
                                                        </div>
                                                    </th>
                                                    <th colspan="4" class="border-bottom border-bottom-dashed ps-0">
                                                        <div class="d-flex align-items-start justify-content-between mb-2">
                                                            <div class="text-grey py-1 fs-6">Subtotal :
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="fs-6 text-gray-800">
                                                                    {{ form.currency.symbol }}{{
                                                                        this.form.total_amount
                                                                    }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                                            <div class="text-grey py-1 fs-6">Tax Rate :</div>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div
                                                                    class="input-group input-group-sm input-group-solid mb-5 w-100px">
                                                                    <input type="text" class="form-control text-end pe-0"
                                                                        v-model="form.tax_rate" @keyup="handler()" :class="v$.form.tax_rate.$errors.length > 0
                                                                            ? 'is-invalid'
                                                                            : ''
                                                                            " />
                                                                    <span class="input-group-text ps-0"
                                                                        id="basic-addon2">%</span>
                                                                    <div v-for="(error, index) of v$.form.tax_rate.$errors"
                                                                        :key="index">
                                                                        <input-error :message="error.$message" />
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                                            <div class="text-grey py-1 fs-6">Conversion Rate :</div>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div
                                                                    class="input-group input-group-sm input-group-solid mb-5 w-100px">
                                                                    <input type="text" class="form-control text-end" :class="v$.form.conversion_rate.$errors.length > 0
                                                                        ? 'is-invalid'
                                                                        : ''
                                                                        " v-model="v$.form.conversion_rate.$model"
                                                                        @keyup="handler()" />
                                                                    <div v-for="(error, index) of v$.form.conversion_rate.$errors"
                                                                        :key="index">
                                                                        <input-error :message="error.$message" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                                            <div class="text-grey py-1 fs-6">Applied Exchange Rate :</div>
                                                            <div class="d-flex align-items-center gap-2">
                                                                1 {{ form.currency.code }} = {{
                                                                    form.conversion_rate }} INR
                                                            </div>
                                                        </div>

                                                        <div class="d-flex align-items-start justify-content-between">
                                                            <div class="text-grey py-1 fs-6">Tax Amount :
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="fs-6 text-gray-800">
                                                                    {{ form.tax_amount }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-start justify-content-between">
                                                            <div class="text-grey py-1 fs-6">Total Amount :
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="fs-6 text-gray-800">
                                                                    {{ form.currency.symbol }}{{
                                                                        form.total_amount
                                                                    }}
                                                                    X INR {{ form.conversion_rate }} =
                                                                    {{ form.currency.code }} {{
                                                                        form.total_amount * form.conversion_rate }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label fs-6 fw-bold text-gray-700">Notes</label>
                                        <textarea name="notes" class="form-control form-control-solid" rows="3"
                                            placeholder="Thanks for your business"
                                            v-model="v$.form.notes.$model"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-lg-auto min-w-lg-300px position-relative">
                    <div class="card position-sticky top-0">
                        <div class="card-body p-10">
                            <div class="mb-5">
                                <label class="form-label fw-bold fs-6 text-gray-700">Currency</label>
                                <select class="form-control form-control-lg form-control-solid mb-2"
                                    v-model="v$.form.currency.$model" :class="v$.form.currency.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " @change="getCurrencyValue($event)">
                                    <option v-for="(currency, index) in currencies.data" :key="currency" :value="currency">
                                        {{
                                            currency.label }}</option>
                                </select>
                                <div v-for="(error, index) of v$.form.currency.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="mb-5">
                                <label class="form-label fw-bold fs-6 text-gray-700">Status</label>
                                <select class="form-control form-control-solid" v-model="v$.form.status.$model" :class="v$.form.status.$errors.length > 0
                                    ? 'is-invalid'
                                    : ''
                                    ">
                                    <option v-for="(status, index) in status" :key="index" :value="status.id">{{
                                        status.label
                                    }}</option>
                                </select>
                                <div v-for="(error, index) of v$.form.status.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="mb-5">
                                <label class="form-label fw-bold fs-6 text-gray-700">Type</label>
                                <select class="form-control form-control-solid" v-model="v$.form.type.$model" :class="v$.form.type.$errors.length > 0
                                    ? 'is-invalid'
                                    : ''
                                    ">
                                    <option value="Invoice">Invoice</option>
                                    <option value="Credit Note">Credit Note</option>
                                    <option value="Quote">Quote</option>
                                    <option value="Purchase Order">Purchase Order</option>
                                    <option value="Receipt">Receipt</option>
                                </select>
                                <div v-for="(error, index) of v$.form.type.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="mb-5">
                                <label class="form-label fw-bold fs-6 text-gray-700">Issue Date</label>
                                <input type="date" v-model="v$.form.issue_date.$model"
                                    class="form-control form-control-solid" :class="v$.form.issue_date.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " placeholder="Select date" />
                                <div v-for="(error, index) of v$.form.issue_date.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>
                            <div class="mb-5">
                                <label class="form-label fw-bold fs-6 text-gray-700" for="daysDropdown">Select Due
                                    Interval:</label>
                                <select class="form-control form-control-solid" v-model="form.selectedDays"
                                    @change="updateDate">
                                    <option value="0">0 days</option>
                                    <option value="15">15 days</option>
                                    <option value="30">30 days</option>
                                    <option value="45">45 days</option>
                                </select>
                            </div>
                            <div class="mb-5">
                                <label class="form-label fw-bold fs-6 text-gray-700">Due Date</label>
                                <input type="date" v-model="v$.form.due_date.$model" class="form-control form-control-solid"
                                    :class="v$.form.due_date.$errors.length > 0
                                        ? 'is-invalid'
                                        : ''
                                        " placeholder="Select date" />
                                <div v-for="(error, index) of v$.form.due_date.$errors" :key="index">
                                    <input-error :message="error.$message" />
                                </div>
                            </div>

                            <div class="separator separator-dashed mb-8"></div>
                            <div class="mb-0">
                                <button type="submit" href="#" class="btn btn-primary w-100" id="kt_invoice_submit_button">
                                    <span class="svg-icon svg-icon-3">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z"
                                                fill="currentColor" />
                                            <path opacity="0.3"
                                                d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    Save Invoice</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
