export interface User {
  id: string;
  name: string;
  email: string;
  role: 'HOTEL_OWNER' | 'SUPER_ADMIN';
  createdAt: Date;
  updatedAt: Date;
}

export interface LoginCredentials {
  email: string;
  password: string;
}

export interface RegisterData {
  name: string;
  email: string;
  password: string;
  role: 'HOTEL_OWNER';
}

export interface ForgotPasswordData {
  email: string;
}

export interface ResetPasswordData {
  token: string;
  password: string;
}

export interface AuthResponse {
  user: User;
  token: string;
}
