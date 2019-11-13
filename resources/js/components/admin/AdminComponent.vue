<template>
  <div>
    <b-container>
      <b-card no-body>
        <b-tabs card justified>
          <b-tab no-body title="Customers">
            <b-tabs pills card vertical>
              <b-tab title="Customers" active>
                <admin-customers-component @toast="toast"></admin-customers-component>
              </b-tab>
              <b-tab title="Orders">
                <b-card-text>Orders</b-card-text>
              </b-tab>
            </b-tabs>
          </b-tab>

          <b-tab no-body title="Products">
            <b-tabs v-model="tabIndex" pills card vertical>
              <b-tab title="Products">
                <admin-products-component @toast="toast" @select="select"></admin-products-component>
              </b-tab>
              <b-tab title="Product Versions">
                <admin-products-productversions-component
                  @toast="toast"
                  @selectProductVersion="selectProductVersion"
                  :product="this.selectedProduct"
                ></admin-products-productversions-component>
              </b-tab>
              <b-tab title="Image">
                <admin-products-images-component
                  @toast="toast"
                  :productVersion="this.selectedProductVersion"
                ></admin-products-images-component>
              </b-tab>
              <b-tab title="Sizes">
                <admin-products-sizes-component
                  @toast="toast"
                  :productVersion="this.selectedProductVersion"
                ></admin-products-sizes-component>
              </b-tab>
            </b-tabs>
          </b-tab>
          <b-tab no-body title="Data">
            <b-tabs pills card vertical>
              <b-tab title="Order Status">
                <b-card-text>Order Status</b-card-text>
              </b-tab>
              <b-tab title="Colors">
                <admin-data-color-component @toast="toast"></admin-data-color-component>
              </b-tab>
              <b-tab title="Waist">
                <admin-data-waist-component @toast="toast"></admin-data-waist-component>
              </b-tab>
              <b-tab title="Length">
                <admin-data-length-component @toast="toast"></admin-data-length-component>
              </b-tab>
            </b-tabs>
          </b-tab>
        </b-tabs>
      </b-card>
    </b-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tabIndex: 0,
      selectedProduct: "",
      selectedProductVersion: ""
    };
  },
  methods: {
    select(id, name) {
      this.selectedProduct = { id, name };

      let title = "Selection";
      let body = "Product " + name + " Selected";
      this.toast("info", title, body);
      this.tabIndex++;
    },
    selectProductVersion(id) {
      this.selectedProductVersion = { id };

      let title = "Selection";
      let body = "Product Version " + id + " Selected";
      this.toast("info", title, body);
      this.tabIndex++;
    },
    toast(variant, title, body) {
      this.$bvToast.toast(body, {
        title: title,
        toaster: "b-toaster-top-left",
        variant: variant,
        solid: true
      });
    }
  },
  computed: {
    rows() {
      return this.items.length;
    }
  }
};
</script>
