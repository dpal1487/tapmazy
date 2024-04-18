<script>
import { defineComponent } from 'vue';

export default defineComponent({

    props: {
        invoice: {
            type: Object,
            default: null
        }
    }

});
</script>
<template>
    <div class="card pt-2">
        <div class="card-header">
            <div class="card-title">
                <h2>Invoices</h2>
            </div>
        </div>
        <div class="card-body pt-0">
            <div id="kt_referred_users_tab_content" class="tab-content">
                <div id="kt_customer_details_invoices_1" class="py-0 tab-pane fade show active" role="tabpanel">
                    <table id="kt_customer_details_invoices_table_1"
                        class="table align-middle table-row-dashed fs-6 fw-bold gy-5">
                        <thead class="border-bottom border-gray-200 fs-7 text-uppercase fw-bold">
                            <tr class="text-start text-muted gs-0">
                                <th class="min-w-100px">Order ID</th>
                                <th class="min-w-100px">Amount</th>
                                <th class="min-w-100px">Status</th>
                                <th class="min-w-125px">Date</th>
                                <th class="min-w-100px text-end pe-7">Invoice</th>
                            </tr>
                        </thead>
                        <tbody class="fs-6 fw-semibold text-gray-600">
                            <tr>
                                <td>

                                    <a href="#" class="text-gray-600 text-hover-primary">{{ invoice?.number }}</a>
                                </td>
                                <td class="text-success">{{ invoice?.amount_due }}</td>
                                <td>
                                    <span class="badge badge-light-danger">{{ invoice?.status }}</span>
                                </td>
                                <td>{{ invoice }}</td>
                                <td class="text-end">
                                    <a :href="invoice.invoice_pdf" target="_blank">Download</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
