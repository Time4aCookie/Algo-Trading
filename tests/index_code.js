const stockPrices = [];
        function getClosePrice(){
            fetch('http://127.0.0.1:5002/close_price')
            .then(response => response.json())
            .then(data =>{
                const closePriceElement = document.getElementById('closePrice');
                const newNumber = document.createElement('div');
                newNumber.textContent = `${data.close_price}`;
                if(newNumber.textContent > 0){
                    closePriceElement.appendChild(newNumber);
                    stockPrices.push(data.close_price);
                }
                if(stockPrices.length >= 2){
                    const profits = document.getElementById("potentialProfits");
                    profits.textContent = 'Had you bought this stock, your profit would be: ' + (parseFloat(stockPrices[stockPrices.length-1]) - parseFloat(stockPrices[0])).toFixed(4);
                }
                if(stockPrices.length == 2){
                    analyzeStock(data.close_price);
                }
            });
        }

        setInterval(getClosePrice, 1500);
        getClosePrice();

        function analyzeStock(closePrice){
            const buyStockElement = document.getElementById('buyStockTechAnalysis');
            const buyStockGBM = document.getElementById('buyStockGBM');
            const analysis = analyze(closePrice); // Pass the latest price to the analyze function
            const GBManalysis = GBManalyze(closePrice);
            if(GBManalysis){
                buyStockGBM.textContent = "According to GBM: Yes, you should buy this stock";
            }
            else{
                buyStockGBM.textContent = "According to GBM: No, you should not buy this stock";
            }
            if (analysis){
                buyStockElement.textContent = "According to technical analysis: Yes, you should buy this stock";
            }
            else{
                buyStockElement.textContent = "According to technical analysis: No, you should not buy this stock";
            }
        }

        function analyze(closePrice){
            if (stockPrices[1] > stockPrices[0]){
                return true;
            }
            else{
                return false;
            }
        }
        function GBManalyze(closePrice){
            const randomValue = Math.random();
            if (randomValue > 0.5){
                return true;
            }
            else{
                return false;
            }
        }
const domContainer = document.querySelector('#buyStockTechAnalysis');
const root = ReactDom.createRoot(domContainer);
root.render(e(BuyOrNot));
