import React from "react";
import "../style.css";
import {
  SummerSlider1,
  SummerSlider2,
  SummerSlider3,
  SummerSlider4,
} from "../Slider";
import { useSpeechSynthesis } from "react-speech-kit";
const Summer = () => {


  const { speak } = useSpeechSynthesis();



  function TextToSpeech1() {
    const { speak, cancel, speaking } = useSpeechSynthesis();
    // const [text, setText] = useState('');

    const handleSpeak = () => {
      if (speaking) {
        cancel();
      } else {
        const content=" Despite the scary name, this is a lovely wildflower native to our Eastern meadows that draws butterflies and birds. One especially showy variety, E. maculatum ‘Gateway,’ has wine-red stems 5 to 7 feet tall, topped by dusky rose nosegays a foot across. Use as a tall anchor in a perennial bed or as a temporary screen, since stems die back to the ground in winter."
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
        const content="These perky yellow or orange flowers really light up a garden bed.The plant’s distinctive (should we say strong?) odor also keeps pests away. Marigolds are great as cut flowers, too. ‘Moonsong Deep Orange,’ a hybrid that has been named an All-America Selection winner, has frilly, densely packed flowers. But many other marigolds look more like daisies, with just a row or two of petals around a dark center. MariGold are comprised of tiny florets surrounded by many layers of delicate, ruffled petals and a thick hollow stem with fernlike leaves. The flowers have a pungent, sharp, and musky aroma that can be considered unpleasant."
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
        const content="This is Also known as onion flower,these strong-stemmed perennials actually have a pleasant scent—only the bulbs may remind you of their garlic and onion cousins. These dense balls of color are usually purple or white and are best suited for the back of your garden, as they're quite tall. They also make excellent, modern looking bouquets. An allium flower head is a cluster of individual florets. The overall shape of this flower cluster can be round, oval or cascading, and the flower color may be white, yellow, pink, purple or blue. Heights also vary, with some alliums standing just 5 inches tall, and others reaching 4 feet. Each type of allium adds its own distinctive style and personality to the garden."
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
        const content="Blanket flower has brightly colored red and/or yellow flowers. With brightly colored daisy-like flowers in shades of red, orange, and yellow, the heat-tolerant and heavy blooming blanket flower is a good addition to the informal garden. They are typically native to hot and dry climates, like tough prairies and rocky plains. These plants thrive in zones 3 to 10 and are well adapted to poor soils and severe drought. They have a long, rich history in their native range of the Americas. Their bright, vivid colors make them a cheerful addition to any floral gift. In the language of flowers, they symbolize charm, joy, happiness, and modesty."
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
        <img class="imgsummer" src="assets/hotsummer10.jpg" />
      </div>
      <div class="secondspringdiv1">
        <div class="quote1">
          <text class="quotetext">
            "In the depth of winter, I finally learned that within me there lay
            an invincible summer."
          </text>
          <div class="authordiv">
            <text class="author">_Henry David_</text>
          </div>
        </div>

        <div class="FloweName">
          <h1></h1>
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#Joe Pye Weed</h1>
          <div class="sliderclass radiusslider">
            <SummerSlider2 />
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
            Despite the scary name, this is a lovely wildflower native to our
            Eastern meadows that draws butterflies and birds. One especially
            showy variety, E. maculatum ‘Gateway,’ has wine-red stems 5 to 7
            feet tall, topped by dusky rose nosegays a foot across. Use as a
            tall anchor in a perennial bed or as a temporary screen, since stems
            die back to the ground in winter.
          </text>
          <text class="h6content">
            Joe Pye weed grows best in full sun to partial shade. Too much shade
            can encourage legginess and cause the plant to flop over. Shady
            conditions also can make the plant susceptible to disease. However,
            Joe Pye weed also appreciates some protection from the hot afternoon
            sun, especially in the summer months
          </text>
          <TextToSpeech1 />
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#MariGold</h1>
          <div class="sliderclass radiusslider">
            <SummerSlider1 />
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
            These perky yellow or orange flowers really light up a garden bed.
            The plant’s distinctive (should we say strong?) odor also keeps
            pests away. Marigolds are great as cut flowers, too. ‘Moonsong Deep
            Orange,’ a hybrid that has been named an All-America Selection
            winner, has frilly, densely packed flowers. But many other marigolds
            look more like daisies, with just a row or two of petals around a
            dark center.
          </text>
          <text class="h6content">
            MariGold are comprised of tiny florets surrounded by many layers of
            delicate, ruffled petals and a thick hollow stem with fernlike
            leaves. The flowers have a pungent, sharp, and musky aroma that can
            be considered unpleasant.
          </text>
          <TextToSpeech2 />
        </div>

        <div class="FlowerSlider">
          <h1 class="springheadingf">#Allium</h1>
          <div class="sliderclass radiusslider">
            <SummerSlider3 />
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
            This is Also known as "onion flower," these strong-stemmed
            perennials actually have a pleasant scent—only the bulbs may remind
            you of their garlic and onion cousins. These dense balls of color
            are usually purple or white and are best suited for the back of your
            garden, as they're quite tall. They also make excellent, modern
            looking bouquets
          </text>
          <text class="h6content">
            An allium flower head is a cluster of individual florets. The
            overall shape of this flower cluster can be round, oval or
            cascading, and the flower color may be white, yellow, pink, purple
            or blue. Heights also vary, with some alliums standing just 5 inches
            tall, and others reaching 4 feet. Each type of allium adds its own
            distinctive style and personality to the garden.
          </text>
          <TextToSpeech3 />
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#Blanket Flower</h1>
          <div class="sliderclass radiusslider">
            <SummerSlider4 />
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
            Blanket flower has brightly colored red and/or yellow flowers. With
            brightly colored daisy-like flowers in shades of red, orange, and
            yellow, the heat-tolerant and heavy blooming blanket flower is a
            good addition to the informal garden.
          </text>
          <text class="h6content">
            They are typically native to hot and dry climates, like tough
            prairies and rocky plains. These plants thrive in zones 3 to 10 and
            are well adapted to poor soils and severe drought. They have a long,
            rich history in their native range of the Americas. Their bright,
            vivid colors make them a cheerful addition to any floral gift. In
            the language of flowers, they symbolize charm, joy, happiness, and
            modesty.
          </text>
          <TextToSpeech4 />
        </div>
      </div>
    </div>
  );
};

export default Summer;
