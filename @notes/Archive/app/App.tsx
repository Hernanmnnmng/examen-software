import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { Navigation } from '@/app/components/Navigation';
import VoorraadPage from '@/app/components/pages/VoorraadPage';
import LeveranciersPage from '@/app/components/pages/LeveranciersPage';
import VoedselpakketPage from '@/app/components/pages/VoedselpakketPage';

export default function App() {
  return (
    <Router>
      <div className="min-h-screen bg-gray-50">
        <Navigation />
        <main className="container mx-auto px-4 py-8">
          <Routes>
            <Route path="/" element={<Navigate to="/voorraad" replace />} />
            <Route path="/voorraad" element={<VoorraadPage />} />
            <Route path="/leveranciers" element={<LeveranciersPage />} />
            <Route path="/voedselpakket" element={<VoedselpakketPage />} />
          </Routes>
        </main>
      </div>
    </Router>
  );
}
