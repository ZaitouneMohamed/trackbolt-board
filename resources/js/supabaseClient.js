
// --- supabaseClient.ts ---
import { createClient } from '@supabase/supabase-js'

export const supabase = createClient(
  'http://127.0.0.1:54321', // Supabase URL (local dev)
  'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZS1kZW1vIiwicm9sZSI6ImFub24iLCJleHAiOjE5ODM4MTI5OTZ9.CRXP1A7WOeoJeXxjNni43kdQwgnWNReilDMblYTn_I0'           // Replace with your actual local anon key
)
