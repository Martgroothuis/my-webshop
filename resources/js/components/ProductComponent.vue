<template>
  <div>
    <b-container>
      <b-card class="mt-3">
        <b-row>
          <b-col cols="12" lg="7">
            <product-images-component
              :images="this.images"
              :mute="this.mute"
              @selectImage="selectImage"
            ></product-images-component>
          </b-col>
          <b-col cols="12" lg="5">
            <!-- componet -->
            <div class="mb-4">
              <h3 class="text-dark">{{this.productData.name}}</h3>
              <h5 class="text-muted" style="height: 1em;">{{this.selectedProduct.color_version}}</h5>
              <hr class="divider" />

              <b-row>
                <b-col class="d-flex justify-content-between mb-2">
                  <h4 class="mr-3">{{ this.productData.price | toCurrency}}</h4>
                  <p
                    class="m-1 text-success"
                    v-if="this.selectedSize.id"
                  >Nog {{this.selectedSize.stock}} beschikbaar</p>
                </b-col>
              </b-row>
              <b-button
                v-if="this.selectedSize.id"
                squared
                block
                variant="success"
                size="lg"
                @click="addShoppingCard()"
              >In Winkelmandje</b-button>
              <b-button v-else squared block variant="success" disabled size="lg">In Winkelmandje</b-button>

              <hr class="divider" />
            </div>

            <b-row>
              <b-col class="pb-3" cols="12" md="6">
                <b-button-group class="w-100" vertical>
                  <h6 class="text-center w-100">Color</h6>
                  <b-button
                    squared
                    block
                    :variant="product_version.version"
                    size="lg"
                    v-for="product_version in product_versions"
                    v-bind:key="product_version.id"
                    @click="selectProduct(product_version.id)"
                  >{{product_version.color_name}}</b-button>
                </b-button-group>
              </b-col>
              <b-col cols="6" md="3">
                <b-button-group class="w-100" vertical>
                  <h6 class="text-center w-100">Waist {{this.selectedWaist_size}}</h6>
                  <b-button
                    squared
                    block
                    size="lg"
                    v-for="size in waist_size"
                    v-bind:key="size.id"
                    v-bind:class="size.classObject"
                    variant="outline-dark"
                    @click="selectSize(size.size, 'Waist_size')"
                  >{{size.size}}</b-button>
                </b-button-group>
              </b-col>
              <b-col cols="6" md="3">
                <b-button-group class="w-100" vertical>
                  <h6 class="text-center w-100">Length {{this.selectedLength_size}}</h6>
                  <b-button
                    squared
                    block
                    size="lg"
                    v-for="size in length_size"
                    v-bind:key="size.id"
                    :class="size.classObject"
                    variant="outline-dark"
                    @click="selectSize(size.size, 'Length_size')"
                  >{{size.size}}</b-button>
                </b-button-group>
              </b-col>
            </b-row>
            <!-- dd -->
          </b-col>
          <!-- <h1>{{this.selectedSize.waist_size}}</h1>
          <h1>{{this.selectedSize.length_size}}</h1>-->
        </b-row>
      </b-card>
    </b-container>
  </div>
</template>

<script>
// een product versie object
function Product_version({
  id,
  order,
  price,
  product_id,
  color_id,
  color,
  color_version,
  size
}) {
  this.id = id;
  this.order = order;
  this.price = price;
  this.product_id = product_id;
  this.color_id = color_id;
  this.color_name = color.name;
  this.color_version = color_version;
  this.classObject = {
    active: true,
    "btn-dark": false
  };
  this.version = "outline-dark";
  this.sizes = size;
}

// een image object
function Image({ id, product_version_id, name, url, thumbnailUrl }) {
  this.id = id;
  this.product_version_id = product_version_id;
  this.name = name;
  this.url = "/images/" + url;
  this.thumbnailUrl = "/images/small/" + url;
  this.classObject = {
    active: true,
    "d-none": false
  };
  this.classThumbnailObject = {
    active: true,
    "bg-dark": false
  };
  this.classThumbnailContainer = {
    active: true,
    "d-none": false
  };
}

function Length_size(size) {
  this.size = size;
  this.classObject = {
    active: false,
    "d-none": false
  };
}

function Waist_size(size) {
  this.size = size;
  this.classObject = {
    active: false,
    "d-none": false
  };
}

export default {
  // de eigenschp van dit compontent
  props: ["product"],

  // de arrays aanmaken
  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
      product_versions: [],
      images: [],
      selectedProduct: [],
      length_sizes: [],
      waist_sizes: [],
      selectedWaist_size: "",
      selectedLength_size: "",
      selectedSize: "",
      productData: "",
      show: true
    };
  },
  computed: {
    length_size() {
      this.length_sizess = [
        ...new Set(this.selectedProduct.sizes.map(i => i.length_size.size))
      ];

      this.length_sizess.forEach(size => {
        this.length_sizes.push(new Length_size(size));
      });
      return this.length_sizes;
    },
    waist_size() {
      this.waist_sizess = [
        ...new Set(this.selectedProduct.sizes.map(i => i.waist_size.size))
      ];

      this.waist_sizess.forEach(size => {
        this.waist_sizes.push(new Waist_size(size));
      });
      return this.waist_sizes;
    }
  },
  methods: {
    formatPrice(value) {
      let val = (value / 1).toFixed(2).replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    addShoppingCard() {
      const data = {
        _token: this.csrf,
        product_version_id: this.selectedProduct.id,
        size_id: this.selectedSize.id
      };
      window.axios.post(`/api/shoppingcart`, data).then(({ data }) => {
        console.log(data);
        window.location = "/shoppingcart";
      });
    },
    read(id) {
      // mute is voor een laad icon
      this.mute = true;
      // alle product versies ophalen van dit hooftproduct het id van dit hooftproduct komt uit de template
      window.axios.get(`/api/productversions/${id}`).then(({ data }) => {
        data.forEach(product_version => {
          this.product_versions.push(new Product_version(product_version));
        });
      });
      // alle plaatjes in een array zetten dit zijn alleen de plaatjes van dit hooftproduct
      window.axios.get(`/api/images/${id}`).then(({ data }) => {
        data.forEach(image => {
          this.images.push(new Image(image));
        });

        //het eerste plaatje selecteren en het eerste product
        this.selectImage(1);
        this.selectProduct();

        this.mute = false;
      });
    },
    // dit is om plaatjes te selecterren om het plaatje groot te kunnen bekijken
    selectImage(select) {
      //alle plaatjes verstoppen en de donkere rand weer wit maken
      this.images.forEach(image => {
        image.classObject["d-none"] = true;
        image.classThumbnailObject["bg-dark"] = false;
      });

      var selectedImage = this.images.find(image => image.id === select);
      // het geselecteerde plaatje weer zichtbaar maken
      selectedImage.classObject["d-none"] = false;
      // de rand van de tumbnail donker maken voor duidelijkheid
      selectedImage.classThumbnailObject["bg-dark"] = true;
    },
    selectSize(select, version) {
      if (version == "Waist_size") {
        this.waist_sizes.forEach(waist_size => {
          waist_size.classObject.active = false;
        });
        this.selectedWaist_size = select;
        this.waist_sizes.find(
          Waist_size => Waist_size.size == select
        ).classObject.active = true;
      }
      if (version == "Length_size") {
        this.length_sizes.forEach(length_size => {
          length_size.classObject.active = false;
        });
        this.selectedLength_size = select;
        this.length_sizes.find(
          Length_size => Length_size.size == select
        ).classObject.active = true;
      }

      if (this.selectedWaist_size != "" && this.selectedLength_size != "") {
        if (
          (this.selectedSize = this.selectedProduct.sizes.find(
            size =>
              size.waist_size.size == this.selectedWaist_size &&
              size.length_size.size == this.selectedLength_size
          ))
        ) {
          this.selectedSize = this.selectedProduct.sizes.find(
            size =>
              size.waist_size.size == this.selectedWaist_size &&
              size.length_size.size == this.selectedLength_size
          );
        } else {
          console.log("not");
          this.selectedSize = "";
          this.selectedWaist_size = "";
          this.selectedLength_size = "";
        }
      }

      console.log(this.selectedSize);
      console.log(this.selectedWaist_size);
      console.log(this.selectedLength_size);
      // console.log(select);
    },
    // dit word aangeroepen als een kleur gekozen word
    selectProduct(select) {
      // om te zorgen dat altijd de eerste geselecteerd word
      if (select == null) {
        select = this.images[0].product_version_id;
      }

      this.selectedSize = "";
      this.selectedWaist_size = "";
      this.selectedLength_size = "";
      this.waist_sizes = [];
      this.length_sizes = [];

      //alle plaatjes verstoppen en de donkere rand weer wit maken
      this.images.forEach(image => {
        image.classObject["d-none"] = true;
        image.classThumbnailContainer["d-none"] = true;
        image.classThumbnailObject["bg-dark"] = false;

        //zorgen dat tubnails geen container laten staan
        if (image.product_version_id == select) {
          image.classThumbnailContainer["d-none"] = false;
        }
      });

      //de kleur van alle buttons niet geselect maken
      this.product_versions.forEach(product_version => {
        product_version.version = "outline-dark";
      });

      var selectedImage = this.images.find(
        image => image.product_version_id === select
      );

      if (selectedImage) {
        //het eerste grote plaatje laten zien
        selectedImage.classObject["d-none"] = false;
        // de rand van de tumbnail donker maken voor duidelijkheid
        selectedImage.classThumbnailObject["bg-dark"] = true;
      }

      //het geselecteerde product opslaan
      this.selectedProduct = this.product_versions.find(
        product_version => product_version.id === select
      );
      console.log(this.selectedProduct);

      // de button van het gelecteerde product donker maken
      this.product_versions.find(
        product_version => product_version.id === this.selectedProduct.id
      ).version = "dark";

      //de prijs updaten van het product
      this.productData.price = this.selectedProduct.price;

      //de component updaten
      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    }
  },
  created() {
    // de data bruikbaar maken
    this.productData = JSON.parse(this.product);
    console.log(this.productData);
    // data ophalen als het component aangemaakt is
    this.read(this.productData.id);
  }
};
</script>