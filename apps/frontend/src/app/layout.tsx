import type { Metadata } from "next";
import { Geist, Geist_Mono } from "next/font/google";
import { Providers } from "./providers";
import "./globals.css";

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  title: "ReNova — Marketplace Électronique Afrique",
  description: "Connectez-vous pour vendre, acheter, échanger et réparer des appareils électroniques en Afrique francophone",
  keywords: ["marketplace", "électronique", "Afrique", "vente", "achat", "réparation"],
  authors: [{ name: "Joël Agbakpem", url: "https://github.com/Jokioholmes" }],
  metadataBase: new URL(process.env.NEXT_PUBLIC_API_URL || "http://localhost:3000"),
  openGraph: {
    title: "ReNova — Marketplace Électronique",
    description: "La référence pour acheter, vendre et réparer des appareils électroniques",
    type: "website",
    locale: "fr_FR",
  },
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html
      lang="fr"
      className={`${geistSans.variable} ${geistMono.variable} h-full antialiased scroll-smooth`}
    >
      <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="theme-color" content="#000066" />
      </head>
      <body className="min-h-screen flex flex-col bg-white text-gray-900 dark:bg-gray-950 dark:text-gray-50">
        <Providers>
          {children}
        </Providers>
      </body>
    </html>
  );
}