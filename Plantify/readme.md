# 🌿 Plantify - Smart Plant Identification System

Plantify is a smart web application developed using **ReactJS** that identifies plant species, diagnoses plant diseases, and provides detailed plant information, including seasonal behavior. It uses image recognition to analyze a plant's **leaf or flower**, and incorporates **ChatGPT** and **voice recognition** to offer a natural, interactive experience for users. This is my Fianl Year Project (FYP).

---

## 🚀 Features

### 🔍 Intelligent Plant Detection

- Upload a **leaf or flower image**
- Receive:
  - Plant species name
  - Botanical biography
  - High-quality disease images
  - Symptoms and treatments

### 🧠 ChatGPT Integration

- Ask anything about the plant using a **chat interface**
- AI-powered responses based on plant context and image analysis

### 🎙️ Voice Recognition

- Speak instead of type! Integrated **speech-to-text** allows hands-free usage

### 🌦️ Seasonal Plant Information

- Discover how your plant behaves in:
  - 🌸 **Spring**
  - ☀️ **Summer**
  - 🍂 **Autumn**
  - ❄️ **Winter**

### 🖼️ Plant Gallery

- A beautiful, scrollable image gallery for plant visualization and comparison

---

## 🧩 Project Structure

```
Plantify/
│
├── API/
│   └── voicerecognition.js        # Voice input processing
│
├── components/
│   ├── input/
│   │   └── Input.jsx              # File/image upload and preprocessing
│   ├── App.css                    # Main styling
│   ├── Autumn.js                  # Autumn season plant info
│   ├── ChatFunctions.js          # ChatGPT request handler
│   ├── ChatGPT.js                # ChatGPT UI and interaction
│   ├── Gallery.js                # Image gallery
│   ├── Navbar.js                 # Navigation bar
│   ├── Spring.js                 # Spring season plant info
│   ├── Summer.js                 # Summer season plant info
│   ├── Winter.js                 # Winter season plant info
│
├── pages/
│   ├── FetchDetails.jsx          # Result fetching and display
│   └── ResultPage.jsx           # Render result after plant detection
│
├── store/
│   └── controls.js               # App-wide state management
│
├── App.js                        # Root component
├── dashmix.css                   # Additional styles
├── index.js                      # React entry point
├── Slider.js                     # Image slider/carousel
└── style.css                     # Global styles
```

---

## 🛠️ Technologies Used

- **ReactJS** – Frontend framework
- **JavaScript (ES6+)** – Core scripting
- **ChatGPT API** – AI chatbot integration
- **Web Speech API** – Voice recognition feature
- **TensorFlow.js** – Image recognition for plant classification
- **CSS Modules** – Styling and layout
- **React Router** – Navigation between pages

---

## 🖼️ How It Works

1. User uploads a **leaf or flower image** from their device
2. App analyzes the image and identifies:
   - Plant species
   - Description & biography
   - Diseases (with images and symptoms)
3. User can:
   - **Chat** with ChatGPT about the plant
   - **Speak** queries using the mic
   - Browse **plant details by season**
4. Displays results in a beautifully designed **gallery** and **result page**

---

## 🧪 Use Cases

- Farmers diagnosing plant diseases
- Gardeners identifying unknown plants
- Students learning botany visually
- Anyone curious about plant life!

---

## 🔧 Setup Instructions

### Prerequisites:

- Node.js >= 14.x
- npm or yarn

### Steps:

```bash
# Clone the repository
git clone https://github.com/ahmad6532/My_Projects/tree/main/Plantify
cd plantify

# Install dependencies
npm install

# Start development server
npm run dev
```

---

## 📦 Future Improvements

- 🌍 Multilingual support
- 📱 Mobile responsiveness
- ☁️ Cloud image processing
- 🧬 Genetic prediction (leaf patterns, diseases)
- 📊 User dashboard with saved plant searches

---

## 🙌 Contributors

- **Muhammad Ahmad** – Developer
- **Zunaira Eman** – Designer

---

## 📄 License

This project is licensed under the **MIT License**.  
Feel free to use, modify, and distribute!

---

## 🌱 Acknowledgements

- [OpenAI](https://openai.com/) – For ChatGPT APIs
- [React](https://reactjs.org/) – For powering the frontend
- [Web Speech API](https://developer.mozilla.org/en-US/docs/Web/API/Web_Speech_API) – For voice interaction
- Any plant image dataset/model used (e.g., PlantVillage)

---

> 🌼 **Plantify** – Know Your Plant. Heal Your Green. Grow Smarter. 🌿
