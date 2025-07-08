import { Doughnut, mixins } from 'vue-chartjs'

export default {

   extends: Doughnut,
  mixins: [mixins.reactiveProp],
  props: ['chartData','options'], 

  mounted () {
    // this.chartData is created in the mixin.
    // If you want to pass options please create a local options object
    this.gradient = this.$refs.canvas
      .getContext("2d")
      .createLinearGradient(0, 0, 0, 450);
    //console.log(this.chartData);

    this.gradient.addColorStop(0, "rgba(255, 0,0, 0.5)");
    this.gradient.addColorStop(0.5, "rgba(255, 0, 0, 0.25)");
    this.gradient.addColorStop(1, "rgba(255, 0, 0, 0)");
    this.renderChart(this.chartData, this.options,this.gradient)
  }
}