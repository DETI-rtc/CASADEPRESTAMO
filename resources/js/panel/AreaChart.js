import { Line, mixins } from 'vue-chartjs'

export default {

  extends: Line,
 // mixins: [mixins.reactiveProp],
   mixins: [mixins.reactiveProp],
  props: ['chartData','options'],
 
  mounted (){
    this.gradient = this.$refs.canvas
      .getContext("2d")
      .createLinearGradient(0, 0, 0, 450);
    this.gradient2 = this.$refs.canvas
      .getContext("2d")
      .createLinearGradient(0, 0, 0, 450);
    //this.nuevo = this.respuesta;
    this.gradient.addColorStop(0, "rgba(255, 0,0, 0.5)");
    this.gradient.addColorStop(0.5, "rgba(255, 0, 0, 0.25)");
    this.gradient.addColorStop(1, "rgba(255, 0, 0, 0)");

    this.gradient2.addColorStop(0, "rgba(0, 231, 255, 0.9)");
    this.gradient2.addColorStop(0.5, "rgba(0, 231, 255, 0.25)");
    this.gradient2.addColorStop(1, "rgba(0, 231, 255, 0)");
    console.log(this.gradient);
    console.log(this.gradient2);
    //axios.get("api/creditomesger").then(({ data }) => {
          //        (this.medato1 = data.grafico1, this.medato2 = data.grafico2, this.labelg = data.labelg );
          //        }).catch(error => { this.error = error.response });
    this.renderChart(this.chartData, this.options)
  }
}