<template>
  <div>
    <b-container>
      <b-card>
        <b-table
          striped
          hover
          outline
          responsive="sm"
          :items="products"
          :fields="fields"
          :busy="mute"
        >
          <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>Loading...</strong>
            </div>
          </template>
        </b-table>
        <b-button variant="success">Betalen</b-button>
      </b-card>
    </b-container>
  </div>
</template>

<script>
function Product({ product_version_id, size_id, ammount }) {
  this.name = product_version_id[0].name.name;
  this.color = product_version_id[0].color.name;

  this.product_version_id = product_version_id;
  this.size_id = size_id;
  this.ammount = ammount;
  this.price = product_version_id[0].price;
}

export default {
  // de eigenschp van dit compontent
  // props: ["data"],

  data() {
    return {
      products: [],
      mute: false,
      fields: [
        "name",
        "color",
        "product_version_id",
        "size_id",
        "ammount",
        "price"
      ],
      products: []
    };
  },
  methods: {
    read() {
      this.mute = true;
      window.axios.get("/api/shoppingcard").then(({ data }) => {
        console.log(data);
        data.forEach(product => {
          this.products.push(new Product(product));
        });

        console.log(this.products);
        this.mute = false;
      });
    }
  },
  created() {
    // this.data = JSON.parse(this.data);

    this.read();
  }
};
</script>
