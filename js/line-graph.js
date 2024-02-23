var xValues = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];

new Chart("myChart", {
type: "line",
data: {
    labels: xValues,
    datasets: [{ 
    data: [100,1140,1060,1060,1070,1110,1330,2210,7830,2478],
    borderColor: "#b3b3ff",
    fill: false
    }, { 
    data: [1200,1400,1700,1900,2000,2700,4000,5000,6000,7000],
    borderColor: "#e6e6ff",
    fill: false
    }]
},
options: {
    legend: {display: false}
}
});