const fun1=()=>{
    let message = '';

    if (flowerName.toLowerCase() === 'fiddle') {
      message = 'Here is some information about fiddle...';
      // Display the details about roses
    } else if (flowerName.toLowerCase() === 'sunflower') {
      message = 'Here is some information about sunflowers history...';
      // Display the details about sunflowers
    } else {
      message = "I'm sorry, I don't have information about that flower.";
    }

    return message;
}
export const reco = new fun1();