<script setup>

</script>
<template>
    <div>
        <div class="font-semibold text-xl mb-4">Chi tiết đơn hàng</div>
        <div class="card">
            <div class="flex flex-col gap-4">
                <div class="flex flex-wrap gap-4">
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="orderName">Tên cửa hàng</label>
                        <InputText type="text" />
                    </div>
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="customerName">Tên khách hàng</label>
                        <InputText type="text"  />
                    </div>
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="totalPrice">Tổng giá trị</label>
                        <InputText type="text"  />
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-4 mt-5">
                <div class="flex flex-wrap gap-4">
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="orderName">Phương thức thanh toán</label>
                        <Select
                            optionLabel="name"
                            optionValue="code"
                            placeholder="Select" />
                    </div>
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="customerName">Phí ship</label>
                        <InputText type="text"  />
                    </div>
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="totalPrice">Thuế</label>
                        <InputText type="text"  />
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col gap-4 mt-5">
                <div class="flex flex-wrap gap-4">
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="orderName">Ngày đặt hàng</label>
                        <DatePicker :showIcon="true" :showButtonBar="true"></DatePicker>
                    </div>
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="customerName">Ngày ship</label>
                        <DatePicker :showIcon="true" :showButtonBar="true"></DatePicker>
                    </div>
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="totalPrice">Ngày hủy đặt hàng</label>
                        <DatePicker :showIcon="true" :showButtonBar="true"></DatePicker>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-4 mt-5">
                <div class="flex flex-wrap gap-4">
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="orderName">Trạng thái đặt hàng</label>
                        <Select
                            optionLabel="name"
                            optionValue="code"
                            placeholder="Select" />
                    </div>
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="customerName">Ghi chú của khách hàng</label>
                        <Textarea rows="5" cols="30" />
                    </div>
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="totalPrice">Detail line</label>
                        <InputText type="text"  />
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-4 mt-5">
                <div class="flex flex-wrap gap-4">
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="orderName">Tên sản phẩm</label>
                        <Select
                            optionLabel="name"
                            optionValue="code"
                            placeholder="Select" />
                    </div>
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="customerName">Giá cả</label>
                        <InputText type="text"  />
                    </div>
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="totalPrice">Số lượng</label>
                        <InputText type="text"  />
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <Button label="Lưu" severity="success" class="mr-2" />
                <Button label="Reset" severity="secondary" />
            </div>
        </div>
    </div>
</template>
