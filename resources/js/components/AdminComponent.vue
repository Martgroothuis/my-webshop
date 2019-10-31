<template>
  <div>
    <b-container>
      <b-card no-body>
        <b-tabs card justified v-model="tabIndex">
          <b-tab title="Customers">
            <admin-customers-component @toast="toast"></admin-customers-component>
          </b-tab>
          <b-tab title="Orders">
            <b-card-text @toast="toast">Bestellingen</b-card-text>
          </b-tab>
          <b-tab title="Products">
            <admin-products-component @toast="toast" @select="select"></admin-products-component>
          </b-tab>
          <b-tab title="Product Versions">
            <admin-product-versions-component @toast="toast" :product="this.selectedProduct"></admin-product-versions-component>
          </b-tab>
          <b-tab title="Image">
            <admin-images-component @toast="toast"></admin-images-component>
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
      tabIndex: 1,
      selectedProduct: ""
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
