<script>
import { defineComponent } from 'vue';

export default defineComponent({
    props: {
        plan: {
            plan: Object
        }
    }

})
</script>

<template>
    <div class="card my-5">
        <div class="card-header">
            <div class="card-title">
                <h2 class="fw-bold">Plan Details</h2>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table  table-row-dashed fs-6 fw-semibold">
                    <thead>
                        <tr class="text-gray-700 text-muted fw-bold fs-7 text-uppercase">
                            <th class="w-150px">Plan Name</th>
                            <th class="w-300px">Plan Description</th>
                            <th class="w-150px">Price</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-500">
                        <tr>
                            <td>{{ plan?.plan_name }}</td>
                            <td>{{ plan?.sort_description }}</td>
                            <td><span class="text-primary">{{ plan?.currency?.symbols + '' +
                                plan?.plan_price }}</span> / {{ plan.plan_type }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
