import React from "react";
import "../style.css";
import {
  WinterSlider1,
  WinterSlider2,
  WinterSlider3,
  WinterSlider4,
} from "../Slider";
import { useSpeechSynthesis } from "react-speech-kit";
const winter = () => {



  const { speak } = useSpeechSynthesis();



  function TextToSpeech1() {
    const { speak, cancel, speaking } = useSpeechSynthesis();
    // const [text, setText] = useState('');

    const handleSpeak = () => {
      if (speaking) {
        cancel();
      } else {
        const content="Poinsettias bear dark green leaves between three and six inches long; cultivars may have pale green, cream, orange, or marbled leaves. The top leaves are a modified form called bracts. These are brightly colored - red in the species, although cultivars with pink or white bracts have been developed.The showy colored parts of Poinsettias that most people think of as the flowers are actually colored bracts (modified leaves). The yellow flowers, or cyathia, are in the center of the colorful bracts. The plant drops its bracts and leaves soon after those flowers shed their pollen. We consider them a Christmas flower, and many people give them around Christmas time to symbolise good will and community spirit. In religious communities, the shape of the poinsettia flower is thought to symbolise the Star of Bethlehem, with the red leaves of the poinsettia symbolising the blood of Christ.It's often used as a Christmas decoration. The whole poinsettia plant and its sap (latex) have been used to make medicine. Chemicals in the dried sap might have pain relieving effects. People use poinsettia for fever, pain, and other conditions"
        speak({ text: content });
      }
    };

    return (
      <div>
        <button onClick={handleSpeak} class="speakerbtn">
          {speaking ? (
            <img src="assets/f3.png" style={{ height: 25, width: 25 }} />
          ) : (
            <img src="assets/f1.png" style={{ height: 25, width: 25 }} />
          )}
        </button>
      </div>
    );
  }
  function TextToSpeech2() {
    const { speak, cancel, speaking } = useSpeechSynthesis();
    // const [text, setText] = useState('');

    const handleSpeak = () => {
      if (speaking) {
        cancel();
      } else {
        const content="Aster is a genus of perennial flowering plants in the family Asteraceae. Its circumscription has been narrowed, and it now encompasses around 170 species, all but one of which are restricted to Eurasia; many species formerly in Aster are now in other genera of the tribe Astereae. According to Ancient Greek myth, the aster flower was created by the goddess Astraea. There are a few different versions of the story, but the most well-known is that Astraea was so upset by the lack of stars in the sky that she began to weep, with asters sprouting from where her tears fell upon the ground.The Aster is a unique daisy-like wildflower that's known for its star-shaped flower head. Aster meanings include love and wisdom. With a rich history in Greek mythology,It's said that the aster was created by the tears of the Greek goddess, Astraea. Asters produce large clusters of flowers in white, purple, lavender, pink, and red. The plants tolerate poor soil and dryness but bloom poorly in dry soil. They grow two to five feet tall and are spaced 15 inches apart. They multiply rapidly so may need frequent division."
        speak({ text: content });
      }
    };

    return (
      <div>
        <button onClick={handleSpeak} class="speakerbtn">
          {speaking ? (
            <img src="assets/f3.png" style={{ height: 25, width: 25 }} />
          ) : (
            <img src="assets/f1.png" style={{ height: 25, width: 25 }} />
          )}
        </button>
      </div>
    );
  }
  function TextToSpeech3() {
    const { speak, cancel, speaking } = useSpeechSynthesis();
    // const [text, setText] = useState('');

    const handleSpeak = () => {
      if (speaking) {
        cancel();
      } else {
        const content="Origin:French. Meaning:Flowering plant with velvety petals; Thought. If you want to give baby a floral name to match their blooming spirit, the name Pansy will make for a lush choice. Heart-shaped or rounded leaves sprout from the base, and oblong or oval leaves grow from the stems. The plant's velvety flowers, which usually occur in combinations of blue, yellow, and white, are about 2.5 to 5 cm (1 to 2 inches) across and have five petals.Leaves are oval or heart-shaped with coarse notches. Flowers, which contain five rounded petals, may consist of a single solid color, a single color with black lines radiating from the center of the bloom, or a face with a different color in the center of the bloom than around the edges.The blotch pansy is arguably the most popular of pansy varieties. The flowers have petals with a number of vibrant colors — from yellow to blue to red — all with a dark purple “blotch” on their faces. Wild pansy has dermatological properties. Its use is particularly recognized in the case of seborrhoeic skin disorders. Numerous studies have been carried out on its action in skin pathologies such as pimple eruptions, acne and irritations. Wild pansy is also used in cases of psoriasis and vulval itching."
        speak({ text: content });
      }
    };

    return (
      <div>
        <button onClick={handleSpeak} class="speakerbtn">
          {speaking ? (
            <img src="assets/f3.png" style={{ height: 25, width: 25 }} />
          ) : (
            <img src="assets/f1.png" style={{ height: 25, width: 25 }} />
          )}
        </button>
      </div>
    );
  }
  function TextToSpeech4() {
    const { speak, cancel, speaking } = useSpeechSynthesis();
    // const [text, setText] = useState('');

    const handleSpeak = () => {
      if (speaking) {
        cancel();
      } else {
        const content="The Hollyhock, (Alcea rosea), herbaceous flowering plant of the hibiscus, or mallow, family (Malvaceae) native to China but widely cultivated for its handsome flowers. The several varieties include annual, biennial, and perennial forms. The name Hollyhock is believed to have derived from the Anglo-Saxon term, 'holy-hoc' or holy mallow – mallow being a common name given to all members of the althea family. The word, althea, comes from the Greek, altheo, meaning, to cure – a reference to the medicinal virtues of the plant. Each flower spans about 3-5 when it is fully open; it has 5 petals, 5 sepals, 6-9 sepal-like bracts, and a columnar structure in the center with the reproductive organs (stamens toward the tip, thread-like stigmas below).Hollyhocks are a short lived perennial. This means that most varieties will only live two to three years. Their lifespan can be extended some by removing growing hollyhock flowers as soon as they fade."
        speak({ text: content });
      }
    };

    return (
      <div>
        <button onClick={handleSpeak} class="speakerbtn">
          {speaking ? (
            <img src="assets/f3.png" style={{ height: 25, width: 25 }} />
          ) : (
            <img src="assets/f1.png" style={{ height: 25, width: 25 }} />
          )}
        </button>
      </div>
    );
  }




  return (
    <div class="maindiv1">
      <div>
        <img class="imgtopspring" src="assets/winter.jpeg" />
      </div>
      <div class="secondspringdiv2">
        <div class="quote2">
          <text class="quotetext">
          "To Appriciate the Beauty of SnowFlake, It is Necessary to Stand out in Cold."
          </text>
          <div class="authordiv">
          <text class="author">
                        
                                 _Aristotle_
          </text>
          </div>
        </div>
        <div class="FloweName">
          <h1></h1>
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#Poinsettia</h1>
          <div class="sliderclass radiusslider">
            <WinterSlider1/>
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
          Poinsettias bear dark green leaves between three and six inches long; cultivars may have pale green, cream, orange, or marbled leaves. The top leaves are a modified form called bracts. These are brightly colored - red in the species, 
          although cultivars with pink or white bracts have been developed.
          The showy colored parts of Poinsettias that most people think of as the flowers are actually colored bracts (modified leaves). The yellow flowers, or cyathia, are in the center of the colorful bracts. The plant drops its bracts and leaves soon after those flowers shed their pollen.
          </text>
          <text class="h6content">
          We consider them a Christmas flower, and many people give them around Christmas time to symbolise good will and community spirit. In religious communities, the shape of the poinsettia flower is thought to symbolise the Star of Bethlehem, 
          with the red leaves of the poinsettia symbolising the blood of Christ.It's often used as a Christmas decoration. The whole poinsettia plant and its sap (latex) have been used to make medicine. Chemicals in the dried sap might have pain relieving effects. People use poinsettia for fever, pain, and other conditions, 
          </text>
          <TextToSpeech1 />
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#Aster</h1>
          <div class="sliderclass radiusslider">
            <WinterSlider2/>
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
          Aster is a genus of perennial flowering plants in the family Asteraceae. Its circumscription has been narrowed, and it now encompasses around 170 species, 
          all but one of which are restricted to Eurasia; many species formerly in Aster are now in other genera of the tribe Astereae.
          According to Ancient Greek myth, the aster flower was created by the goddess Astraea. There are a few different versions of the story, but the most well-known is that Astraea was so upset by the lack of stars in the sky that she began to weep, with asters sprouting from where her tears fell upon the ground.

         
          </text>
          <text class="h6content">
          The Aster is a unique daisy-like wildflower that's known for its star-shaped flower head. Aster meanings include love and wisdom. With a rich history in Greek mythology,
           it's said that the aster was created by the tears of the Greek goddess, Astraea. Asters produce large clusters of flowers in white, purple, lavender, pink, and red. The plants tolerate poor soil and dryness but bloom poorly in dry soil. They grow two to five feet tall and are spaced 15 inches apart. They multiply rapidly so may need frequent division.
          </text>
          <TextToSpeech2 />
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#Pansy</h1>
          <div class="sliderclass radiusslider">
            <WinterSlider3/>
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
          Origin:French. Meaning:Flowering plant with velvety petals; Thought. If you want to give baby a floral name to match their blooming spirit, the name Pansy will make for a lush choice.
          Heart-shaped or rounded leaves sprout from the base, and oblong or oval leaves grow from the stems. The plant's velvety flowers, which usually occur in combinations of blue, yellow, and white, are about 2.5 to 5 cm (1 to 2 inches) across and have five petals.
          Leaves are oval or heart-shaped with coarse notches. Flowers, which contain five rounded petals, may consist of a single solid color, a single color with black lines radiating from the center of the bloom, or a "face" with a different color in the center of the bloom than around the edges.
          </text>
          <text class="h6content">
          The blotch pansy is arguably the most popular of pansy varieties. The flowers have petals with a number of vibrant colors — from yellow to blue to red — all with a dark purple “blotch” on their faces.
          Wild pansy has dermatological properties. Its use is particularly recognized in the case of seborrhoeic skin disorders. Numerous studies have been carried out on its action in skin pathologies such as pimple eruptions, acne and irritations. Wild pansy is also used in cases of psoriasis and vulval itching.
          </text>
          <TextToSpeech3 />
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#HollyHock</h1>
          <div class="sliderclass radiusslider">
            <WinterSlider4/>
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
          The Hollyhock, (Alcea rosea), herbaceous flowering plant of the hibiscus, or mallow, family (Malvaceae) native to China but widely cultivated for its handsome flowers. The several varieties include annual, biennial, and perennial forms.
          The name Hollyhock is believed to have derived from the Anglo-Saxon term, 'holy-hoc' or holy mallow – mallow being a common name given to all members of the althea family. The word, althea, comes from the Greek, altheo, meaning, to cure – a reference to the medicinal virtues of the plant.
          </text>
          <text class="h6content">
          Each flower spans about 3-5" when it is fully open; it has 5 petals, 5 sepals, 6-9 sepal-like bracts, and a columnar structure in the center with the reproductive organs (stamens toward the tip, thread-like stigmas below).
          Hollyhocks are a short lived perennial. This means that most varieties will only live two to three years. Their lifespan can be extended some by removing growing hollyhock flowers as soon as they fade.
          </text>
          <TextToSpeech4 />
        </div>
      </div>
    </div>
  );
};

export default winter;
