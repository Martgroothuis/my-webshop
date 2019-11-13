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
          <template v-slot:cell(image)="row">
            <b-img-lazy
              thumbnail
              :src="row.item.thumbnailUrl"
              width="100"
              fluid
              alt="Responsive image"
            ></b-img-lazy>
          </template>
          <template v-slot:cell(price)="row">{{row.item.price * row.item.ammount | toCurrency}}</template>
          <template v-slot:cell(actions)="row">
            <b-button-group class="mx-1">
              <b-button @click="updatemin(row.item.id, row.item.ammount)">-</b-button>
              <b-button @click="updateplus(row.item.id, row.item.ammount, row.item.stock)">+</b-button>
            </b-button-group>

            <b-button variant="danger" @click="del(row.item.id)">Delete</b-button>
          </template>

          <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>Loading...</strong>
            </div>
          </template>
        </b-table>
        <h6>Shipping: {{this.shippingCost | toCurrency}}</h6>
        <hr class="divider" />
        <h5>Total ex btw ex shipping: {{this.total / 100 * 79 | toCurrency}}</h5>
        <hr class="divider" />
        <h3>Total: {{this.total + this.shippingCost | toCurrency}}</h3>
        <hr class="divider" />

        <a href="/payments" class="btn btn-success">Pay</a>
        <b-button variant="danger" @click="destroy()">Delete</b-button>
      </b-card>
    </b-container>
  </div>
</template>

<script>
function Product({ id, product_version_id, size_id, ammount }) {
  this.id = id;
  this.thumbnailUrl = "/images/small/" + product_version_id.image[0].url;
  this.product = product_version_id.product.name;
  this.color = product_version_id.color.name;

  // this.product_version_id = product_version_id.id;
  this.size = size_id.waist_size.size + " x " + size_id.length_size.size;
  this.stock = size_id.stock;
  this.ammount = ammount;
  this.price = product_version_id.price;
}

export default {
  // de eigenschp van dit compontent
  // props: ["data"],

  data() {
    return {
      products: [],
      mute: false,
      total: "",
      shippingCost: 5.95,
      fields: [
        "image",
        "product",
        "color",
        "size",
        "ammount",
        "price",
        "actions"
      ],
      products: []
    };
  },
  methods: {
    totals() {
      var sum = 0;
      this.products.forEach(function(product) {
        sum += product.price * product.ammount;
      });
      this.total = sum;
      console.log(sum);
    },

    updateplus(id, val, maxammount) {
      if (val < maxammount) {
        val++;
        this.update(id, val);
      }
    },
    updatemin(id, val) {
      if (val > 1) {
        val--;
        this.update(id, val);
      }
    },

    update(id, ammount) {
      window.axios
        .put(`/api/shoppingcart/${id}`, {
          _token: this.csrf,
          ammount: ammount
        })
        .then(({ data }) => {
          console.log(data);
          this.products.find(product => product.id === id).ammount = ammount;
          this.totals();
        });
    },

    read() {
      this.mute = true;
      window.axios.get("/api/shoppingcart").then(({ data }) => {
        console.log(data);
        if (data) {
          data.forEach(product => {
            this.products.push(new Product(product));
          });
          this.totals();
          console.log(this.products);
        } else {
          this.products = [];
        }

        this.mute = false;
      });
    },
    del(id) {
      window.axios
        .put(`/api/shoppingcart/${id}`, {
          _token: this.csrf,
          ammount: 0
        })
        .then(({ data }) => {
          let index = this.products.findIndex(product => product.id === id);
          this.products.splice(index, 1);
          this.totals();
        });
    },
    destroy() {
      window.axios.delete("/api/shoppingcart/1").then(() => {
        this.read();
      });
      this.totals();
    }
  },
  created() {
    // this.data = JSON.parse(this.data);

    this.read();
  }
};
</script>
