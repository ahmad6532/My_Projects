import React from "react";
import "../style.css";
import {
  AutumnSlider1,
  AutumnSlider2,
  AutumnSlider3,
  AutumnSlider4,
} from "../Slider";
import { useSpeechSynthesis } from "react-speech-kit";
const Autumn = () => {


  const { speak } = useSpeechSynthesis();



  function TextToSpeech1() {
    const { speak, cancel, speaking } = useSpeechSynthesis();
    // const [text, setText] = useState('');

    const handleSpeak = () => {
      if (speaking) {
        cancel();
      } else {
        const content="Dahlias are tuberous perennials, and most have simple leaves that are segmented and toothed or cut. The compound flowers may be white, yellow, red, or purple in colour. Wild species of dahlias have both disk and ray flowers in the flowering heads, but many varieties of ornamentals such as the common garden dahlia. Today, dahlia flowers symbolize beauty, commitment, and kindness. They're also tied to steadfastness, due to their ability to bloom after many other flowers have died.Their flowers can range from dinnerplate size to petite pompoms. Native to the Andes of South America, this daisy-family plant is a tender perennial in most areas of the country, but is hardy outdoors in USDA zones 8 to 10. In zone 7, they can survive winters with a thick layer of protective mulch. How Long Do Dahlias Bloom? The bloom time for dahlias is an impressive 120 days. That means that if your dahlias start blossoming in mid-July, you can expect to enjoy the beautiful blooms through mid-November. Long after many of your other flowers have died, dahlias will continue to bring color to your garden."
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
        const content=" Lilies typically feature 6-tepaled flowers in a variety of shapes (trumpet, funnel, cup, bell, bowl or flat), sometimes nodding, sometimes with reflexed petals, atop stiff, unbranched stems (1-8' tall) clothed with linear to elliptic leaves. Flowers are often fragrant and come in a broad range of colors except blue. The lily is incredible for pollinators, attracting insects with its large colorful flowers and tasteful nectar. Certain species of lily are pollinated by wind, while others are pollinated by bees! Lilies have large petals that can be white, yellow, orange, red, purple or pink in color. The name lily comes from the Latin word for this type of flower, “lilium. The flowers represent purity, innocence and rebirth: in religious iconography, they often represent the Virgin Mary, and are also often depicted at the Resurrection of Christ The lily is a unique and interesting flower. Did you know that the lily flower symbolizes purity and refined beauty? Based on the color or type of the lily, the flower can convey different meanings. The pistil contains the stigma, which pollen sticks to; the style, which the pollen travels through; and the ovary, where the pollen meets the egg cell and fertilization occurs. Lilies are an example of a perfect flower. Lilies have long been associated with love, devotion, purity and fertility. The sweet and innocent beauty of the flower has ensured it remains tied to the ideas of fresh new life and rebirth."
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
        const content=" Penstemon, the beard-tongue genus of the mint order (Lamiales), containing about 250 species of plants native to North America, particularly the western United States. The flowers are usually large and showy, tubular, and bilaterally symmetrical and have four fertile stamens and one sterile stamen (staminode). A flower, sometimes known as a bloom or blossom, is the reproductive structure found in flowering plants (plants of the division Angiospermae). Flowers produce gametophytes, which in flowering plants consist of a few haploid cells which produce gametes. Penstemon digitalis (known by the common names foxglove beard-tongue, foxglove beardtongue, talus slope penstemon, and white beardtongue) is a species of flowering plant in the plantain family, Plantaginaceae. Penstemon plants are herbaceous perennials that feature lance-shaped foliage and spikes of tubular flowers. Flower colors include pink, red, white, purple, and (rarely) yellow. The nickname beardtongue refers to the pollen-free stamen that protrudes from the flower, resembling a bearded iris in this aspect. It is analgesic and a decoction of the root was taken for menstrual pain and stomach ache. Cold infusions or powdered Red Penstemon plant was applied to burns, wounds and sores. A decoction of the plant was taken for cough and infusions were taken as a diuretic."
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
        const content="The plants grow some 30–90 cm (1–3 feet) tall with narrow gray-green leaves. They produce papery flower heads surrounded by bracts. The flower heads have blue, pink, or white ray flowers that are attractive to butterflies. Cornflower is an herb. The dried flowers are used to make medicine. People take cornflower tea to treat fever, constipation, water retention, and chest congestion. They also take it as a tonic, bitter, and liver and gallbladder stimulant.An annual or biennial herb to 80 cm tall with long lasting blue, white or pink flowers on tough wiry stems. Odd plants may have white or pink flowers. The leaves are narrow, covered with cotton like hair and may have a few teeth on the margins. Centaurea cyanus, commonly known as cornflower or bachelor's button, is an annual flowering plant in the family Asteraceae native to Europe. In the past, it often grew as a weed in cornfields (in the broad sense of corn, referring to grains, such as wheat, barley, rye, or oats), hence its name. Cornflowers symbolize love, fertility, tenderness, unity, the future, hope, anticipation, devotion, fidelity, reliability, remembrance, and delicacy, in addition to prosperity and wealth. In the Victorian language of flowers, they represented celibacy. In the wild, germination is mainly in the autumn and winter, but some can germinate following spring cultivations. Easy to grow from seed sown any time from August to late April, but best sown before the end of March."
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
        <img class="imgtopspring" src="assets/Autumn10.avif" />
      </div>
      <div class="secondspringdiv2">
        <div class="quote3">
          <text class="quotetext">
          "Autumn leaves Shower Like Gold,Like Rainbows,as the Winds of of Change begins to Blow."
          </text>
          <div class="authordiv">
          <text class="author">
                        
                                 _Dan MillMan_
          </text>
          </div>
        </div>
        <div class="FloweName">
          <h1></h1>
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#Dahlia</h1>
          <div class="sliderclass radiusslider">
            <AutumnSlider1/>
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
          Dahlias are tuberous perennials, and most have simple leaves that are segmented and toothed or cut. The compound flowers may be white, yellow, red, or purple in colour. Wild species of dahlias have both disk and ray flowers in the flowering heads, but many varieties of ornamentals such as the common garden dahlia.
          Today, dahlia flowers symbolize beauty, commitment, and kindness. They're also tied to steadfastness, due to their ability to bloom after many other flowers have died.
          </text>
          <text class="h6content">
          Their flowers can range from dinnerplate size to petite pompoms. Native to the Andes of South America, this daisy-family plant is a tender perennial in most areas of the country, but is hardy outdoors in USDA zones 8 to 10. In zone 7, they can survive winters with a thick layer of protective mulch.
          How Long Do Dahlias Bloom? The bloom time for dahlias is an impressive 120 days. That means that if your dahlias start blossoming in mid-July, you can expect to enjoy the beautiful blooms through mid-November. Long after many of your other flowers have died, dahlias will continue to bring color to your garden. 
          </text>
          <TextToSpeech1 />
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#Lily</h1>
          <div class="sliderclass radiusslider">
            <AutumnSlider2/>
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
          Lilies typically feature 6-tepaled flowers in a variety of shapes (trumpet, funnel, cup, bell, bowl or flat), sometimes nodding, sometimes with reflexed petals, atop stiff, unbranched stems (1-8' tall) clothed with linear to elliptic leaves. Flowers are often fragrant and come in a broad range of colors except blue.
          The lily is incredible for pollinators, attracting insects with its large colorful flowers and tasteful nectar. Certain species of lily are pollinated by wind, while others are pollinated by bees! Lilies have large petals that can be white, yellow, orange, red, purple or pink in color.
          The name lily comes from the Latin word for this type of flower, “lilium." The flowers represent purity, innocence and rebirth: in religious iconography, they often represent the Virgin Mary, and are also often depicted at the Resurrection of Christ
          </text>
          <text class="h6content">
          The lily is a unique and interesting flower. Did you know that the lily flower symbolizes purity and refined beauty? Based on the color or type of the lily, the flower can convey different meanings.
          The pistil contains the stigma, which pollen sticks to; the style, which the pollen travels through; and the ovary, where the pollen meets the egg cell and fertilization occurs. Lilies are an example of a perfect flower.
          Lilies have long been associated with love, devotion, purity and fertility. The sweet and innocent beauty of the flower has ensured it remains tied to the ideas of fresh new life and rebirth.
          </text>
          <TextToSpeech2 />
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#Penstemon</h1>
          <div class="sliderclass radiusslider">
            <AutumnSlider3/>
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
          Penstemon, the beard-tongue genus of the mint order (Lamiales), containing about 250 species of plants native to North America, particularly the western United States. The flowers are usually large and showy, tubular, and bilaterally symmetrical and have four fertile stamens and one sterile stamen (staminode).
          A flower, sometimes known as a bloom or blossom, is the reproductive structure found in flowering plants (plants of the division Angiospermae). Flowers produce gametophytes, which in flowering plants consist of a few haploid cells which produce gametes.
          </text>Penstemon digitalis (known by the common names foxglove beard-tongue, foxglove beardtongue, talus slope penstemon, and white beardtongue) is a species of flowering plant in the plantain family, Plantaginaceae.
          <text class="h6content">
          Penstemon plants are herbaceous perennials that feature lance-shaped foliage and spikes of tubular flowers. Flower colors include pink, red, white, purple, and (rarely) yellow. The nickname "beardtongue" refers to the pollen-free stamen that protrudes from the flower, resembling a bearded iris in this aspect.
          It is analgesic and a decoction of the root was taken for menstrual pain and stomach ache. Cold infusions or powdered Red Penstemon plant was applied to burns, wounds and sores. A decoction of the plant was taken for cough and infusions were taken as a diuretic.
          </text>
          <TextToSpeech3 />
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#CornFlower</h1>
          <div class="sliderclass radiusslider">
            <AutumnSlider4/>
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
          The plants grow some 30–90 cm (1–3 feet) tall with narrow gray-green leaves. They produce papery flower heads surrounded by bracts. The flower heads have blue, pink, or white ray flowers that are attractive to butterflies.
          Cornflower is an herb. The dried flowers are used to make medicine. People take cornflower tea to treat fever, constipation, water retention, and chest congestion. They also take it as a tonic, bitter, and liver and gallbladder stimulant.
          An annual or biennial herb to 80 cm tall with long lasting blue, white or pink flowers on tough wiry stems. Odd plants may have white or pink flowers. The leaves are narrow, covered with cotton like hair and may have a few teeth on the margins.
          Centaurea cyanus, commonly known as cornflower or bachelor's button, is an annual flowering plant in the family Asteraceae native to Europe. In the past, it often grew as a weed in cornfields (in the broad sense of "corn", referring to grains, such as wheat, barley, rye, or oats), hence its name.
          </text>
          <text class="h6content">
          Cornflowers symbolize love, fertility, tenderness, unity, the future, hope, anticipation, devotion, fidelity, reliability, remembrance, and delicacy, in addition to prosperity and wealth. In the Victorian language of flowers, they represented celibacy.
          In the wild, germination is mainly in the autumn and winter, but some can germinate following spring cultivations. Easy to grow from seed sown any time from August to late April, but best sown before the end of March.
          </text>
          <TextToSpeech4 />
        </div>
      </div>
    </div>
  );
};

export default Autumn;

