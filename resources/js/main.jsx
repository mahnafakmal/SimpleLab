import React from 'react'
import { createRoot } from 'react-dom/client'
import App from './App'
import '../css/simplelab-react.css'

const el = document.getElementById('simplelab-root')
if(el){
  const root = createRoot(el)
  root.render(<App />)
}
