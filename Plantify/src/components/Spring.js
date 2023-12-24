import React from "react";
import "../style.css";
import {
  SpringSlider1,
  SpringSlider2,
  SpringSlider3,
  SpringSlider4,
} from "../Slider";
import { useSpeechSynthesis } from "react-speech-kit";
const Spring = () => {

  const { speak } = useSpeechSynthesis();

  function TextToSpeech1() {
    const { speak, cancel, speaking } = useSpeechSynthesis();
    // const [text, setText] = useState('');

    const handleSpeak = () => {
      if (speaking) {
        cancel();
      } else {
        const content=" Cherry blossoms, know Cherry blossoms, known for their delicate beauty and ephemeral nature, are enchanting flowers that bloom exclusively during the spring season. These captivating blossoms, originating from East Asia, particularly Japan, hold deep cultural significance and evoke a sense of wonder and tranquility. With their brief but magnificent display, They are a symbolic flower of the spring, a time of renewal, and the fleeting nature of life. Their life is very short. After their beauty peaks around two weeks, the blossoms start to fall. During this season in Japan, people like to have cherry blossom parties with colleagues, friends, and family."
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
        const content="The Daffodils, spring-growing flowers of vibrant colors and delicate charm, symbolize beauty and renewal. Originating in Europe, North Africa, and Asia, these perennial plants have spread worldwide, adorning gardens and landscapes. With their trumpet-shaped corolla and diverse colors, daffodils create a striking display, heralding the arrival of spring. Symbolizing new beginnings and triumph over adversity, these blooms have inspired poets and artists, leaving an indelible mark on literature and imagination. Explore the captivating world of daffodils as we unravel their beauty, symbolism, and eternal connection to the cycles of nature.This plants have a single flower on a long green stalk, with green leaves growing from the base of the stem. The flowers have yellow or white petals surrounding a trumpet, which can be a similar or contrasting colour.Daffodils grow between 5 to 80cm tall."
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
        const content="The tulip, a vibrant and elegant flower, is a beloved symbol of spring. With its stunning colors and graceful petals, it brings a burst of beauty and joy as winter fades away. Originating from Europe, North Africa, and Asia, the tulip has become an iconic bloom, especially in the Netherlands. Its association with the arrival of spring signifies new beginnings and the rejuvenation of nature. Admired for its wide range of colors, the tulip evokes happiness and admiration. Throughout history, tulips have held cultural significance, such as during the Tulip Mania in the 17th century. it's Size is 4 to 28 inches.Tulips are diiferent from other flowers because they are self-pollinating, they do not need the pollen to move several feet to another plant but only within their blossoms.Tulips can be propagated through bulb offsets, seeds or micropropagation.Tulip. Undoubtedly one of the most beautiful flowers in the world, tulips are bulbous showy blooms with six distinct petals."
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
        const content="  The Bluebell flowers, scientifically known as Hyacinthoides non-scripta, are renowned for their vibrant blue blooms that adorn woodlands and meadows. These delicate, bell-shaped flowers add a touch of enchantment to spring landscapes. Standing tall on slender stems, bluebells captivate with their modest size yet striking presence. As a spring-growing plant, they symbolize renewal and the awakening of nature. Join us as we delve into the world of bluebells, exploring their characteristics,symbolism, and cultural significance throughout history.The bluebell is a herb that grows from a bulb. It has linear leaves and a flowering stem that grows up to 50 cm tall and droops to one side. The sweet-scented, nodding heads of flowers are bell-shaped and can be violet-blue and sometimes white or pastel pink."
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
        <img class="imgtopspring" src="assets/top.jpeg" />
      </div>
      <div class="secondspringdiv">
        <div class="quote">
          <text class="quotetext">
          "The day the Lord created hope Was
            probably the same day he created Spring."
          </text>
          <div class="authordiv">
          <text class="author">
                        
                                 _Bernard Williams_
          </text>
          </div>
        </div>
        <div class="FloweName">
          <h1></h1>
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#Cherry Blossoms</h1>
          <div class="sliderclass radiusslider">
            <SpringSlider1 />
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
            Cherry blossoms, know Cherry blossoms, known for their delicate
            beauty and ephemeral nature, are enchanting flowers that bloom
            exclusively during the spring season. These captivating blossoms,
            originating from East Asia, particularly Japan, hold deep cultural
            significance and evoke a sense of wonder and tranquility. With their
            brief but magnificent display,
          </text>
          <text class="h6content">
            They are a symbolic flower of the spring, a time of
            renewal, and the fleeting nature of life. Their life is very short.
            After their beauty peaks around two weeks, the blossoms start to
            fall. During this season in Japan, people like to have cherry
            blossom parties with colleagues, friends, and family.
          </text>
          <TextToSpeech1 />
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#Daffodils</h1>
          <div class="sliderclass radiusslider">
            <SpringSlider2 />
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
                The Daffodils, spring-growing flowers of vibrant colors and delicate
                charm, symbolize beauty and renewal. Originating in Europe,
                North Africa, and Asia, these perennial plants have spread
                worldwide, adorning gardens and landscapes. With their
                trumpet-shaped corolla and diverse colors, daffodils create a
                striking display, heralding the arrival of spring. Symbolizing
                new beginnings and triumph over adversity, these blooms have
                inspired poets and artists, leaving an indelible mark on
                literature and imagination. Explore the captivating world of
                daffodils as we unravel their beauty, symbolism, and eternal
                connection to the cycles of nature.
          </text>
          <text class="h6content">
          This plants have a single flower on a long green stalk, with green leaves growing from the base of the stem. 
          The flowers have yellow or white petals surrounding a trumpet, which can be a similar or contrasting colour.
           Daffodils grow between 5 to 80cm tall.
          </text>
          <TextToSpeech2 />
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#Tulip</h1>
          <div class="sliderclass radiusslider">
            <SpringSlider3 />
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
                The tulip, a vibrant and elegant flower, is a beloved symbol of
                spring. With its stunning colors and graceful petals, it brings
                a burst of beauty and joy as winter fades away. Originating from
                Europe, North Africa, and Asia, the tulip has become an iconic
                bloom, especially in the Netherlands. Its association with the
                arrival of spring signifies new beginnings and the rejuvenation
                of nature. Admired for its wide range of colors, the tulip
                evokes happiness and admiration. Throughout history, tulips have
                held cultural significance, such as during the "Tulip Mania" in
                the 17th century. it's Size is 4 to 28 inches.
          </text>
          <text class="h6content">
          Tulips are diiferent from other flowers because they are self-pollinating, they do not need the pollen to move several feet to another plant but only within their blossoms." 
          Tulips can be propagated through bulb offsets, seeds or micropropagation.Tulip. Undoubtedly one of the most beautiful flowers in the world, tulips are bulbous showy blooms with six distinct petals.
          </text>
          <TextToSpeech3 />
        </div>
        <div class="FlowerSlider">
          <h1 class="springheadingf">#Bluebell</h1>
          <div class="sliderclass radiusslider">
            <SpringSlider4 />
          </div>
        </div>
        <div class="FlowerContent">
          <text class="h6content">
                 The Bluebell flowers, scientifically known as Hyacinthoides
                non-scripta, are renowned for their vibrant blue blooms that
                adorn woodlands and meadows. These delicate, bell-shaped flowers
                add a touch of enchantment to spring landscapes. Standing tall
                on slender stems, bluebells captivate with their modest size yet
                striking presence. As a spring-growing plant, they symbolize
                renewal and the awakening of nature. Join us as we delve into
                the world of bluebells, exploring their characteristics,
                symbolism, and cultural significance throughout history.
          </text>
          <text class="h6content">
          The bluebell is a herb that grows from a bulb. It has linear leaves and a flowering stem that grows up to 50 cm tall and droops to one side. 
          The sweet-scented, nodding heads of flowers are bell-shaped and can be violet-blue and sometimes white or pastel pink.
          </text>
          <TextToSpeech4 />
        </div>
      </div>
    </div>
  );
};

export default Spring;
