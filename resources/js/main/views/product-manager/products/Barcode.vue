<template>
	<div>
		<a-button type="text" size="small" @click="showModal">
			<template #icon>
				<BarcodeOutlined />
			</template>
			{{ $t("product.view_barcode") }}
		</a-button>

		<div id="printThis" style="display: none">
			<vue-barcode
				:value="itemCode + ''"
				:options="{ height: 75, format: barcodeSymbology }"
				tag="svg"
				v-for="n in 24"
				:key="n"
			/>
		</div>

		<a-modal
			v-model:visible="visible"
			:title="$t('product.barcode')"
			:footer="false"
			@ok="handleOk"
		>
			<p style="text-align: center">
				<vue-barcode
					:value="itemCode + ''"
					:options="{ height: 75, format: barcodeSymbology }"
					tag="svg"
				></vue-barcode>
			</p>
			<a-button type="primary" @click="printDiv('printThis')" block>
				<template #icon>
					<PrinterOutlined />
				</template>
				{{ $t("product.print_barcode") }}
			</a-button>
		</a-modal>
	</div>
</template>
<script>
import { defineComponent, ref } from "vue";
import { BarcodeOutlined, PrinterOutlined } from "@ant-design/icons-vue";

export default defineComponent({
	props: ["itemCode", "barcodeSymbology"],
	components: {
		BarcodeOutlined,
		PrinterOutlined,
	},
	setup() {
		const visible = ref(false);

		const showModal = () => {
			visible.value = true;
		};

		const handleOk = (e) => {
			visible.value = false;
		};

		const printDiv = (divName) => {
			var divContents = document.getElementById(divName).innerHTML;
			var newWindow = window.open("", "", "height=500, width=500");

			newWindow.document.write("<body >");
			newWindow.document.write(divContents);
			newWindow.document.write("</body></html>");
			newWindow.document.close();
			newWindow.print();
		};

		return {
			visible,
			showModal,
			handleOk,
			printDiv,
		};
	},
});
</script>
